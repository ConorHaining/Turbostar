<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Schedule;
use App\Models\Association;
use App\Models\Tiploc;

class AssociationCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $association;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($association)
    {
        $this->association = $association;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      $association = new Association();
      $association->start_date = $this->association->assoc_start_date;
      $association->end_date = $this->association->assoc_end_date;
      $association->running_days = $this->association->assoc_days;
      $association->base_location_suffix = $this->association->base_location_suffix;
      $association->assoc_location_suffix = $this->association->assoc_location_suffix;


      $association->main_train = $this->association->main_train_uid;
      $association->assoc_train = $this->association->assoc_train_uid;
      $association->category = $this->association->category;
      $association->date_indicator = $this->association->date_indicator;
      $association->location = $this->association->location;
      $association->stp_indicator = $this->association->CIF_stp_indicator;

      if($association->fails_validation)
      {
        return false;
        fail();
      }

      return $association->save();

    }
}
