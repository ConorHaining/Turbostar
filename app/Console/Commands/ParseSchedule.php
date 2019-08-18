<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App;
use App\Jobs\ScheduleCreate;
use App\Jobs\ScheduleDelete;
use App\Jobs\AssociationCreate;
use App\Jobs\AssociationDelete;
use App\Jobs\TiplocCreate;
use App\Jobs\TiplocDelete;
use Aws\Glacier\GlacierClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Queue;
use Redis;

class ParseSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:schedule {--file=} {--full}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads and queues the daily Schedule file from Network Rail';

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
        if($this->option('file') == null && $this->option('full') == null) {
            $fileURL = env("NR_DAILY_SCHEDULE_URL") . $this->scheduleDayCode();
            $filePath = $this->downloadFile($fileURL);
        } else if($this->option('full')) {
            $fileURL = env("NR_FULL_SCHEDULE_URL");
            $filePath = $this->downloadFile($fileURL);
        } else {
            $filePath = storage_path('app/schedule/' . $this->option('file'));
        }
        
        Log::channel('slack_schedule')->info('Today\'s ('.$this->formatFilename().') SCHEDULE has started to queue.');
        $filePath = $this->decompressFile($filePath);

        $counter = fopen($filePath, 'r');
        $lines = 0;
        while (!feof($counter)) {
            fgets($counter);
            $lines++;
        }
        fclose($counter);

        $scheduleJSON = fopen($filePath, 'r');

        // $headerLine = fgets($scheduleJSON);

        // if(!$this->isHeaderValid($headerLine)) {
        //     throw new \Exception('Already used header');
        // }
        $this->info('SCHEDULE contains ' . $lines . ' lines.');
        $bar = $this->output->createProgressBar($lines);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% <info>%elapsed:6s%/%estimated:-6s%</info> %message%');
        $bar->setProgressCharacter("\xF0\x9F\x9A\x82");
        $bar->start();

        while(!feof($scheduleJSON)){
            $queueSize = Redis::dbSize();
            $line = fgets($scheduleJSON);

            while($queueSize >= 100000) {
                $bar->setMessage('<warn>Too many keys in Redis, pausing for 1 minute</warn>');
                sleep(60);
                $queueSize = Redis::dbSize();
            }

            // if (strpos($line, 'JsonAssociationV1') !== false) {
            //     $this->queueAssociation($line);
            // } else if (strpos($line, 'TiplocV1') !== false) {
            //     $this->queueTiploc($line);
            // } else 
            if (strpos($line, 'JsonScheduleV1') !== false) {
                $this->queueSchedule($line);
            }

            $bar->advance();
        }

        $bar->finish();

        fclose($scheduleJSON);

        Log::channel('slack_schedule')->info('Today\'s ('.$this->formatFilename().') SCHEDULE has been successfully queued.');
      
    }

     /**
      * Download the daily SCHEDULE file and store it
      *
      * @see    https://wiki.openraildata.com/index.php/SCHEDULE
      * @return void
      */
    public function downloadFile($fileURL)
    {
        
        // $fileURL = 'https://datafeeds.networkrail.co.uk/ntrod/CifFileAuthenticate?type=CIF_ALL_FULL_DAILY&day=toc-full';
        $fileLocalPath = storage_path('app/schedule/' . $this->formatFilename() . '.gz');

        $fileHandler = fopen($fileLocalPath, "w");

        $curl = curl_init($fileURL);
        curl_setopt($curl, CURLOPT_FILE, $fileHandler);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_USERPWD, env('NR_USERNAME') .':'. env('NR_PASSWORD'));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($curl);
        

        return $fileLocalPath;
    }

    public function decompressFile($fileLocalPath)
    {
        $fileLocalPathDecompressed = storage_path('app/schedule/' . $this->formatFilename() . '.json');

        $sfp = gzopen($fileLocalPath, "rb");
        $fp = fopen($fileLocalPathDecompressed, "w");

        while ($string = gzread($sfp, 4096)) {
            fwrite($fp, $string, strlen($string));
        }
        gzclose($sfp);
        fclose($fp);

        return $fileLocalPathDecompressed;
    }

     /**
      * Create SCHEDULE filename of the format YYYY-MM-DD
      *
      * @return string
      */
    private function formatFilename()
    {
        $date = Carbon::today();

        return $date->format('Y-m-d');
    }

      /**
       * Check if the header for the SCHEDULE has already been downloaded
       *
       * @return boolean
       */
    public function isHeaderValid(string $header)
    {

        $json = json_decode($header);

        $sequenceNumber = $json->JsonTimetableV1->Metadata->sequence;

        $sequenceQuery = DB::table('schedule_sequence')->where('sequence', $sequenceNumber)->first();

        if (is_null($sequenceQuery)) {
            DB::table('schedule_sequence')->insert(
                ['sequence' => $sequenceNumber]
            );
               return true;
        } else {
            return false;
        }

    }

       /**
        *
        *
        *
        *
        */
    public function queueSchedule($payload)
    {
        $scheduleJSON = json_decode($payload);
        $scheduleJSON =  $scheduleJSON->JsonScheduleV1;
        if ($scheduleJSON->transaction_type == "Create") {

            ScheduleCreate::dispatch($scheduleJSON)->onQueue('schedule');

        } else if ($scheduleJSON->transaction_type == "Delete") {

            ScheduleDelete::dispatch($scheduleJSON)->onQueue('schedule');

        } else {

            throw new \Exception("Unknown Schedule Transaction Type", 1);
            // TODO Log this if it ever happens
        }

    }

        /**
         *
         *
         *
         *
         */
    public function queueAssociation($payload)
    {
        $associationJSON = json_decode($payload);
        $associationJSON =  $associationJSON->JsonAssociationV1;
        if ($associationJSON->transaction_type == "Create") {

             AssociationCreate::dispatch($associationJSON)->onQueue('association');

        } else if ($associationJSON->transaction_type == "Delete") {

            AssociationDelete::dispatch($associationJSON)->onQueue('association');

        } else {

            throw new \Exception("Unknown Association Transaction Type", 1);
            // TODO Log this if it ever happens
        }

    }

    public function queueTiploc($payload)
    {
        $tiplocJSON = json_decode($payload);
        $tiplocJSON =  $tiplocJSON->TiplocV1;
        if ($tiplocJSON->transaction_type == "Create") {

             TiplocCreate::dispatch($tiplocJSON)->onQueue('tiploc');

        } else if ($tiplocJSON->transaction_type == "Delete") {

            TiplocDelete::dispatch($tiplocJSON)->onQueue('tiploc');

        } else if ($tiplocJSON->transaction_type == "tiploc") {

            throw new \Exception("Unknown Tiploc Transaction Type", 1);
            // TODO Log this if it ever happens

        } else {

            throw new \Exception("Unknown Tiploc Transaction Type", 1);
            // TODO Log this if it ever happens
        }

    }

        /**
         * This function looks a the current numeric day, following ISO-8601, and returns
         * the short code for the previous day.
         * 
         * @see https://wiki.openraildata.com/index.php/SCHEDULE#Downloading
         */
    private function scheduleDayCode()
    {
        switch(date('N')) {
        case 1:
            return 'sun';
        case 2:
            return 'mon';
        case 3:
            return 'tue';
        case 4:
            return 'wed';
        case 5:
            return 'thu';
        case 6:
            return 'fri';
        case 7:
            return 'sat';
        }
    }
}
