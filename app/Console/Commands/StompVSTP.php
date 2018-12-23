<?php

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

use App\Jobs\ScheduleVSTPCreate;

class StompVSTP extends Command
{
    use InteractsWithTime;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stomp:vstp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $consumer = new Client('tcp://datafeeds.networkrail.co.uk:61618');
        $consumer->setLogin(env('NR_USERNAME'), env('NR_PASSWORD'));
        $consumer->getConnection()->setReadTimeout(1);
        // set clientId on a consumer to make it durable
        $consumer->setClientId(env('STOMP_VSTP_NAME'));

        
        // subscribe to the topic
        try {
            $durableConsumer = new DurableSubscription($consumer, '/topic/VSTP_ALL', null, 'client');
            $durableConsumer->activate();
            $msg = false;
        } catch (ConnectionException $e) {
            $this->error($e->getMessage());
            $this->error('VSTP Feed could not start');

            Log::channel('slack_stomp')->emergency('Movement Feed could not start');

            return;
            
        }
        
        while(Cache::get('stomp.stop') != $this->currentTime()) {
            
            try{
                $msg = $durableConsumer->read();
            } catch (Exception $e) {
                $durableConsumer->inactive();
                $consumer->disconnect();
                Log::channel('slack_stomp')->emergency('VSTP feed has stopped');
            } catch (MissingReceiptException $e) {
                Log::channel('slack_stomp')->critical('Missing Receipt Exception', ['message' => $e->getMessage()]);
            } catch (ConnectionException $e) {
                $this->error($e->getMessage());
                $this->error('Timeout: ' . pow(2, $this->timeoutCount) . 's');

                sleep(pow(2, $this->timeoutCount));
                $this->timeoutCount++;

                if (pow(2, $this->timeoutCount) >= 30) {
                    Log::channel('slack_stomp')->Emergency('VSTP Stomp Client disconnected for ' . pow(2, $this->timeoutCount) . ' seconds');
                    Log::channel('slack_stomp')->Emergency('Stopping VSTP Feed');

                    break;

                }

                continue;
                
            }

            if ($msg != null) {
                $json = json_decode($msg->body)->VSTPCIFMsgV1;
                
                $this->info('VSTP Schedule received: ' . date('H:i:s.u'));
                
                $json->timestamp = now()->format('U') * 1000;
                
                ScheduleVSTPCreate::dispatch($json)->onQueue('schedule-create');
                
                try{
                    $durableConsumer->ack($msg);
                } catch (Exception $e) {
                    $durableConsumer->inactive();
                    $consumer->disconnect();
                    Log::channel('slack_stomp')->emergency('Movement feed has stopped');
                } catch (MissingReceiptException $e) {
                    Log::channel('slack_stomp')->critical('Missing Receipt Exception', ['message' => $e->getMessage()]);
                }
            }
        }


        $durableConsumer->inactive();
        $consumer->disconnect();
        $this->alert('Disconnecting consumer');
        Log::channel('slack_stomp')->warn('Movement feed has gracefully stopped');
    }
}
