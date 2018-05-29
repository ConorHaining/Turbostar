<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\TiplocModel;

class TiplocCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tiploc;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tiploc)
    {
        $this->tiploc = $tiploc;
    }

    /**
     * Execute the job.
     *
     * @return boolean
     */
    public function handle()
    {
        $tiploc = TiplocModel::create([
          'code' => $this->tiploc->tiploc_code,
          'nalco' => $this->tiploc->nalco,
          'stanox' => $this->tiploc->stanox,
          'crs' => $this->tiploc->crs_code,
          'description' => $this->tiploc->description,
          'name' => $this->tiploc->tps_description
        ]);

        return $tiploc->save();
    }
}
