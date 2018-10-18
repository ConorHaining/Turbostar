<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Stomp\Client;
use Stomp\StatefulStomp;
use Stomp\Transport\Message;
use Stomp\Broker\ActiveMq\Mode\DurableSubscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

use App\Jobs\MovementCreate;

class StompMovement extends Command
{
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
        $consumer->setClientId('Turbostar');

        
        // subscribe to the topic
        $durableConsumer = new DurableSubscription($consumer, '/topic/TRAIN_MVT_ALL_TOC', null, 'client');
        $durableConsumer->activate();
        $msg = false;
        
        while(!Cache::has('stomp.stop')) {
            
            try{
                $msg = $durableConsumer->read();
            } catch (Exception $e) {
                $durableConsumer->inactive();
                $consumer->disconnect();
                Log::emergency('Movement feed has stopped');
            } catch (MissingReceiptException $e) {
                Log::critical('Missing Receipt Exception', ['message' => $e->getMessage()]);
            }

            if ($msg != null) {
                $json = json_decode($msg->body);

                foreach($json as $item){
                    $this->info('Message '. $item->header->msg_type .' Received: ' . date('H:i:s.u'));
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

        Cache::pull('stomp.stop');
        // disconnect durable consumer
        $durableConsumer->inactive();
        $consumer->disconnect();
        $this->alert('Disconnecting consumer');
        Log::warn('Movement feed has stopped');
    }

}