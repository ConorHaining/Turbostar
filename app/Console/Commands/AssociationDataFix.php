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

class AssociationDataFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:association';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A hot fix for association data';

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
        Log::channel('slack_schedule')->info('Association Data Fix has started to queue.');

        $filePath = $this->downloadDailyFile();

        $filePath = $this->decompressFile($filePath);

        $scheduleJSON = fopen($filePath, 'r');

        while(!feof($scheduleJSON)){
            $line = fgets($scheduleJSON);

            if (strpos($line, 'JsonAssociationV1') !== false) {
                $this->queueAssociation($line);
            }
        }

        fclose($scheduleJSON);

        Log::channel('slack_schedule')->info('Association Data Fix has been successfully queued.');
      
    }

     /**
      * Download the daily SCHEDULE file and store it
      *
      * @see    https://wiki.openraildata.com/index.php/SCHEDULE
      * @return void
      */
    public function downloadDailyFile()
    {
        $fileURL = 'https://datafeeds.networkrail.co.uk/ntrod/CifFileAuthenticate?type=CIF_ALL_FULL_DAILY&day=toc-full';
        $fileLocalPath = storage_path('app/schedule/ASSOC' . $this->formatFilename() . '.gz');

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
}
