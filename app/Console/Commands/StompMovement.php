<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Stomp\Client;
use Stomp\StatefulStomp;
use Stomp\Transport\Message;
use Stomp\Broker\ActiveMq\Mode\DurableSubscription;
use Illuminate\Support\Facades\Log;

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

    private $halt;

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
        declare(ticks=100); // Handle ticks within this code block
        pcntl_signal(SIGINT, [$this, 'shutdown']); // Call $this->shutdown() on SIGINT
        pcntl_signal(SIGTERM, [$this, 'shutdown']); // Call $this->shutdown() on SIGTERM
        $this->halt = false;

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
        // var_dump($durableConsumer);
        while(!$this->halt) {
            
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


        // disconnect durable consumer
        $durableConsumer->inactive();
        $consumer->disconnect();
        echo "Disconnecting consumer\n";
        Log::emergency('Movement feed has stopped');
    }

    public function shutdown() {
        $this->halt = true;
    }
}
