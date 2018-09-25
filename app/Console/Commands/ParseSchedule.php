<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\ScheduleCreate;
use App\Jobs\ScheduleDelete;
use App\Jobs\AssociationCreate;
use App\Jobs\AssociationDelete;
use App\Jobs\TiplocCreate;
use App\Jobs\TiplocDelete;

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

      $this->decompressFile($filePath);


    }

    /**
     * Download the SCHEDULE file and store it
     *
     * @see https://wiki.openraildata.com/index.php/SCHEDULE
     * @return void
     */
     public function downloadFullFile()
     {
       $fileURL = env("NR_FULL_SCHEDULE_URL");
       $fileLocalPath = __DIR__ . env('NR_SCHEDULE_FILE_PATH') . $this->formatFilename() . '.gz2';

       $fileHandler = fopen($fileLocalPath, "w+");

       $curl = curl_init($fileURL);
       curl_setopt($curl, CURLOPT_FILE, $fileHandler);
       curl_setopt($curl, CURLOPT_TIMEOUT, 60);
       curl_exec($curl);

       return $fileLocalPath;
     }

     /**
      * Download the daily SCHEDULE file and store it
      *
      * @see https://wiki.openraildata.com/index.php/SCHEDULE
      * @return void
      */
      public function downloadDailyFile()
      {
        $fileURL = env("NR_DAILY_SCHEDULE_URL");
        $fileLocalPath = __DIR__ . env('NR_SCHEDULE_FILE_PATH') . $this->formatFilename() . '.gz2';

        $fileHandler = fopen($fileLocalPath, "w");

        $curl = curl_init($fileURL);
        curl_setopt($curl, CURLOPT_FILE, $fileHandler);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_exec($curl);

        return $fileLocalPath;
      }

      public function decompressFile($fileLocalPath)
      {
        $fileLocalPathDecompressed = __DIR__ . env('NR_SCHEDULE_FILE_PATH') . $this->formatFilename() . '.json';

        $sfp = gzopen($fileLocalPath, "rb");
        $fp = fopen($fileLocalPathDecompressed, "w");

        while ($string = gzread($sfp, 4096)) {
          fwrite($fp, $string, strlen($string));
        }
        gzclose($sfp);
        fclose($fp);
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

         $sequenceQuery = DB::collection('header')->where('sequence', $sequenceNumber)->first();

         if (is_null($sequenceQuery)) {
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
}
