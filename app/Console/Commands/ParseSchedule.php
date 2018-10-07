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

class ParseSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:schedule {--file=}';

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
      if($this->option('file') == null){
        $filePath = $this->downloadDailyFile();
      } else {
        $filePath = $this->option('file');
      }

      $filePath = $this->decompressFile($filePath);

      if (App::environment('production')) {
        $glacierClient = GlacierClient::factory([
          'credentials' => [
              'key'    => env('AWS_ACCESS_KEY_ID'),
              'secret' => env('AWS_SECRET_ACCESS_KEY'),
          ],
          'region' => 'eu-west-1'
        ]);
  
        $archiveResult = $glacierClient->uploadArchive([
          'vaultName' => 'NR_SCHEDULE',
          'accountId' => '-',
          'body' => Storage::get('schedule/' . $this->formatFilename() . '.gz'),
        ]);
      }
      
      $scheduleJSON = fopen($filePath, 'r');

      $headerLine = fgets($scheduleJSON);

      if(!$this->isHeaderValid($headerLine)){
        throw new \Exception('Already used header');
      }

      while(!feof($scheduleJSON)){
        $line = fgets($scheduleJSON);

        if (strpos($line, 'JsonAssociationV1') !== false) {
          $this->queueAssociation($line);
        } else if (strpos($line, 'TiplocV1') !== false) {
          $this->queueTiploc($line);
        } else if (strpos($line, 'JsonScheduleV1') !== false) {
          $this->queueSchedule($line);
        }
      }

      fclose($scheduleJSON);

      Log::info('Today\'s ('.$this->formatFilename().') SCHEDULE has been sucessfully queued.');
      
    }

     /**
      * Download the daily SCHEDULE file and store it
      *
      * @see https://wiki.openraildata.com/index.php/SCHEDULE
      * @return void
      */
      public function downloadDailyFile()
      {
        $fileURL = env("NR_DAILY_SCHEDULE_URL") . $this->scheduleDayCode();
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

            ScheduleCreate::dispatch($scheduleJSON)->onQueue('schedule-create');

          } else if ($scheduleJSON->transaction_type == "Delete") {

            ScheduleDelete::dispatch($scheduleJSON)->onQueue('schedule-delete');

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

             AssociationCreate::dispatch($associationJSON)->onQueue('association-create');

           } else if ($associationJSON->transaction_type == "Delete") {

             AssociationDelete::dispatch($associationJSON)->onQueue('association-delete');

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

             TiplocCreate::dispatch($tiplocJSON)->onQueue('tiploc-create');

           } else if ($tiplocJSON->transaction_type == "Delete") {

             TiplocDelete::dispatch($tiplocJSON)->onQueue('tiploc-delete');

           } else if ($tiplocJSON->transaction_type == "Update") {

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
