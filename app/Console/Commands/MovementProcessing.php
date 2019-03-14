<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use \DateTimeZone;
use Queue;
use App\Jobs\MovementCreate;

class MovementProcessing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:movement {startDate} {endDate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The base location where the mirror is stored
     * 
     * @var string
     */
    private $base = 'https://networkrail.opendata.opentraintimes.com/mirror/trust/';

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
        $startDate = new Carbon($this->argument('startDate'), new DateTimeZone('Europe/London'));
        $endDate = new Carbon($this->argument('endDate'), new DateTimeZone('Europe/London'));
        $period = CarbonPeriod::since($startDate)->hours(1)->until($endDate);

        $bar = $this->output->createProgressBar(count($period));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% <info>%elapsed:6s%/%estimated:-6s%</info> <fg=black;bg=cyan>%message%</>');
        // $bar->setRedrawFrequency(100);
        $bar->setMessage('Start');
        $bar->start();

        foreach ($period as $key => $date) {
            $queueSize = Queue::size('movement');
            $bar->setMessage('trust-' . $date->format('YmdHi') . '.log.gz');
            $url = $this->base . $date->format('Y/m/d/') . 'trust-' . $date->format('YmdHi') . '.log.gz';
            
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if($statusCode == 200) {
                $fileLocalPath = storage_path('app/movement/' . 'trust-' . $date->format('YmdHi') . '.log.gz');

                $fileHandler = fopen($fileLocalPath, "w");

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_FILE, $fileHandler);
                curl_setopt($curl, CURLOPT_TIMEOUT, 60);
                // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_exec($curl);
                curl_close($curl);


                $fileLocalPathDecompressed = storage_path('app/movement/' . 'trust-' . $date->format('YmdHi') .'.json');

                $sfp = gzopen($fileLocalPath, "rb");
                $fp = fopen($fileLocalPathDecompressed, "w");

                while ($string = gzread($sfp, 4096)) {
                    fwrite($fp, $string, strlen($string));
                }
                gzclose($sfp);
                fclose($fp);

                $json = file_get_contents($fileLocalPathDecompressed);
                $json = str_replace('}}][{', '}},{', $json);
                try {
                    $json = json_decode($json, false, 512, JSON_THROW_ON_ERROR|JSON_FORCE_OBJECT);
                } catch (\JsonException $e) {
                    $this->error($e->getMessage() . '| ' . 'trust-' . $date->format('YmdHi'));
                }
                
                foreach ($json as $item) {
                    
                    while($queueSize >= 200000) {
                        $bar->setMessage('Too many keys in Redis, pausing for 1 minute');
                        sleep(60);
                        $queueSize = Queue::size('movement');
                    }
                    $item->header->received_at = now()->format('U') * 1000;
                    MovementCreate::dispatch($item)->onQueue('movement');
                }

            }

            $bar->advance();
        }
        $bar->finish();

        // $this->info($date->format('Y/m/d/') . 'trust-' .+ $date->format('YmdHi') . '.log.gz');
    }
}
