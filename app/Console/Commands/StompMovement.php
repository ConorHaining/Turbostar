<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Stomp\Client;
use Stomp\StatefulStomp;
use Stomp\Transport\Message;
use Stomp\Broker\ActiveMq\Mode\DurableSubscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\InteractsWithTime;
use Stomp\Exception\ConnectionException;

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
    }
    

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        // create a consumer
        $consumer = new Client('tcp://datafeeds.networkrail.co.uk:61617');
        $consumer->setLogin(env('NR_USERNAME'), env('NR_PASSWORD'));
        $consumer->getConnection()->setReadTimeout(1);
        // set clientId on a consumer to make it durable
        $consumer->setClientId('Turbostar-Test');

        
        // subscribe to the topic
        try {
            $durableConsumer = new DurableSubscription($consumer, '/topic/TRAIN_MVT_ALL_TOC', null, 'client');
            $durableConsumer->activate();
            $msg = false;
        } catch (ConnectionException $e) {
            $this->error($e->getMessage());

            sleep(pow(2, $this->timeoutCount));
            $this->timeoutCount++;

            $this->handle();
            
        }
        
        while(Cache::get('stomp.stop') != $this->currentTime()) {
            
            try{
                $msg = $durableConsumer->read();
            } catch (Exception $e) {
                $durableConsumer->inactive();
                $consumer->disconnect();
                Log::emergency('Movement feed has stopped');
            } catch (MissingReceiptException $e) {
                Log::critical('Missing Receipt Exception', ['message' => $e->getMessage()]);
            } catch (ConnectionException $e) {
                $this->error($e->getMessage());
                $this->error('Timeout: ' . pow(2, $this->timeoutCount) . 's');

                sleep(pow(2, $this->timeoutCount));
                $this->timeoutCount++;

                if (pow(2, $this->timeoutCount) >= 30) {
                    Log::Emergency('Movement Stomp Client disconnected for ' . pow(2, $this->timeoutCount) . ' seconds');
                }

                continue;
                
            }

            if ($msg != null) {
                $json = json_decode($msg->body);

                foreach($json as $item){
                    $this->info('Message '. $item->header->msg_type .' Received: ' . date('H:i:s.u'));

                    $item->header->received_at = now()->format('U') * 1000;

                    MovementCreate::dispatch($item)->onQueue('movement-' . $item->header->msg_type);
                }
                
                try{
                    $durableConsumer->ack($msg);
                } catch (Exception $e) {
                    $durableConsumer->inactive();
                    $consumer->disconnect();
                    Log::emergency('Movement feed has stopped');
                } catch (MissingReceiptException $e) {
                    Log::critical('Missing Receipt Exception', ['message' => $e->getMessage()]);
                }
            }
        }

        $durableConsumer->inactive();
        $consumer->disconnect();
        $this->alert('Disconnecting consumer');
        Log::warn('Movement feed has gracefully stopped');
    }

}