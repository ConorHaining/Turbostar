<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tiploc;
use PHPCoord\OSRef;

class ParseNaPTAN extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:naptan {--file=}';

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
        $fileName = storage_path('app/' . $this->option('file'), 'r');
        $fileHandler = fopen($fileName, 'r');

        while ($line = fgetcsv($fileHandler)) {
            
            $tiplocDocument = Tiploc::where('code', $line[1])
                                        ->get();

            if($tiplocDocument->total > 0) {
                $OSRef = new OSRef(intval($line[6]), intval($line[7])); //Easting, Northing
                $LatLng = $OSRef->toLatLng();

                $tiplocDocument[0]->location = $LatLng->getLat() . ', ' . $LatLng->getLng();
                $tiplocDocument[0]->save();   
            }
        }

        fclose($fileHandler);

    }
}
