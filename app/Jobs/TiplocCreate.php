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
        // dd($this);
    }

    /**
     * Execute the job.
     *
     * @return boolean
     */
    public function handle()
    {
        $tiploc = new TiplocModel();
        $tiploc->code = $this->tiploc->tiploc_code;
        $tiploc->nalco = $this->tiploc->nalco;
        $tiploc->stanox = $this->tiploc->stanox;
        $tiploc->crs = $this->tiploc->crs_code;
        $tiploc->description = $this->tiploc->description;
        $tiploc->name = $this->tiploc->tps_description;
        
        return $tiploc->save();
    }
}
