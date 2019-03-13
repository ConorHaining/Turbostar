<?php

declare(ticks = 1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Stomp\Client;
use Stomp\StatefulStomp;
use Stomp\Transport\Message;
use Stomp\Broker\ActiveMq\Mode\DurableSubscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\InteractsWithTime;
use Stomp\Exception\ConnectionException;
use Stomp\Exception\MissingReceiptException;

use App\Jobs\MovementCreate;

class StompMovement extends Command
{
    use InteractsWithTime;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stomp:movement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The timeout value, in seconds
     * 
     * @var int
     */
    private $timeoutCount = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        pcntl_signal(SIGTERM, [$this, "sig_handler"]);
        pcntl_signal(SIGHUP,  [$this, "sig_handler"]);
        pcntl_signal(SIGUSR1, [$this, "sig_handler"]);
        pcntl_signal(SIGINT, [$this, "sig_handler"]);
        pcntl_signal(SIGQUIT, [$this, "sig_handler"]);
    }
    

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        // create a consumer
        $consumer = new Client('tcp://datafeeds.networkrail.co.uk:61618');
        $consumer->setLogin(env('NR_USERNAME'), env('NR_PASSWORD'));
        $consumer->getConnection()->setReadTimeout(1);
        // set clientId on a consumer to make it durable
        $consumer->setClientId(env('STOMP_MOVEMENT_NAME'));

        
        // subscribe to the topic
        try {
            $durableConsumer = new DurableSubscription($consumer, '/topic/TRAIN_MVT_ALL_TOC', null, 'client');
            $durableConsumer->activate();
            $msg = false;
        } catch (ConnectionException $e) {
            $this->error($e->getMessage());
            $this->error('Movement Feed could not start');

            Log::channel('slack_stomp')->Emergency('Movement Feed could not start');

            return;
            
        }
        
        while($this->currentTime() >= Cache::get('stomp.stop')) {
            
            try{
                $msg = $durableConsumer->read();
            } catch (Exception $e) {
                $durableConsumer->inactive();
                $consumer->disconnect();
                Log::channel('slack_stomp')->emergency('Movement feed has stopped');
            } catch (MissingReceiptException $e) {
                Log::channel('slack_stomp')->critical('Missing Receipt Exception', ['message' => $e->getMessage()]);
            } catch (ConnectionException $e) {
                $this->error($e->getMessage());
                $this->error('Timeout: ' . pow(2, $this->timeoutCount) . 's');

                sleep(pow(2, $this->timeoutCount));
                $this->timeoutCount++;

                if (pow(2, $this->timeoutCount) >= 30) {
                    Log::channel('slack_stomp')->Emergency('Movement Stomp Client disconnected for ' . pow(2, $this->timeoutCount) . ' seconds');
                    Log::channel('slack_stomp')->Emergency('Stopping Movement Feed');

                    break;

                }

                continue;
                
            }

            if ($msg != null) {
                $json = json_decode($msg->body);

                if (is_array($json) || is_object($json)){

                    foreach($json as $item){
                        $this->info('Message '. $item->header->msg_type .' Received: ' . date('H:i:s.u'));
    
                        $item->header->received_at = now()->format('U') * 1000;
    
                        MovementCreate::dispatch($item)->onQueue('movement');
                    }
                    
                    try{
                        $durableConsumer->ack($msg);
                    } catch (Exception $e) {
                        $durableConsumer->inactive();
                        $consumer->disconnect();
                        Log::channel('slack_stomp')->emergency('Movement feed has stopped');
                    } catch (MissingReceiptException $e) {
                        // Log::channel('slack_stomp')->critical('Missing Receipt Exception', ['message' => $e->getMessage()]);
                    }
                    
                }

                
            }
        }

        $durableConsumer->inactive();
        $consumer->disconnect();
        $this->alert('Disconnecting consumer');
        Log::channel('slack_stomp')->warn('Movement feed has gracefully stopped');
    }

    public function sig_handler() {
        Log::channel('slack_stomp')->emergency('Unexpected signal, shutting down all STOMP connections.');
        Cache::forever('stomp.stop', $this->currentTime());
    }

}