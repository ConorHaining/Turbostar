<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Association;
use App\Models\Schedule;
use App\Models\Tiploc;

class AssociationDelete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $association;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->association = $payload;
    }

    /**
     * Execute the job.
     *
     * @return int
     */
    public function handle()
    {

        $expiredAssociation = Association::where('start_date', '=', $this->association->assoc_start_date)
                                        ->where('stp_indicator', 'like', $this->association->CIF_stp_indicator)
                                        ->where('main_train', 'like', $this->association->main_train_uid)
                                        ->where('assoc_train', 'like', $this->association->assoc_train_uid)
                                        ->first();
        $expiredAssociation->active = false;

        return $expiredAssociation->save();
    }
}
