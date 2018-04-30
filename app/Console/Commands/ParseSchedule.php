<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ParseSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        //
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
       $fileLocalPath = __DIR__ . env('NR_SCHEDULE_FILE_PATH') . "file.gz2";

       $fileHandler = fopen($fileLocalPath, "w+");

       $curl = curl_init($fileURL);
       curl_setopt($curl, CURLOPT_FILE, $fileHandler);
       curl_setopt($curl, CURLOPT_TIMEOUT, 60);
       curl_exec($curl);
     }
}
