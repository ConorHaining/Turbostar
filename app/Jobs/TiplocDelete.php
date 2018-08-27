<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\TiplocModel;

class TiplocDelete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tiploc;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->tiploc = $payload;
    }

    /**
     * Execute the job.
     *
     * @return int
     */
    public function handle()
    {
        $expiredTiploc = TiplocModel::where('code', 'like', 'WLGFSTN')->first();
        $expiredTiploc->active = false;
        
        return $expiredTiploc->save();
    }
}
