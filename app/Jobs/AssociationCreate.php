<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\ScheduleModel;
use App\AssociationModel;
use App\TiplocModel;

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
      $mainTrain = ScheduleModel::where('uid', $this->association->main_train_uid)->get();

      if(empty($mainTrain))
      {
        throw new \Exception("Error Processing Request", 1);

      }

      $assocTrain = ScheduleModel::where('uid', $this->association->assoc_train_uid)->get();

      if(empty($assocTrain))
      {
        throw new \Exception("Error Processing Request", 1);

      }

      $location = TiplocModel::where('code', $this->association->location)->get();

      if(empty($location))
      {
        throw new \Exception("Error Processing Request", 1);

      }

      $association = AssociationModel::create([
        'start_date' => $this->association->assoc_start_date,
        'end_date' => $this->association->assoc_end_date,
        'running_days' => $this->association->assoc_days,
        'base_location_suffix' => $this->association->base_location_suffix,
        'assoc_location_suffix' => $this->association->assoc_location_suffix,
      ]);


      $association->main_train = $mainTrain;
      $association->assoc_train = $assocTrain;
      $association->category = $this->association->category;
      $association->date_indicator = $this->association->date_indicator;
      $association->location = $location;
      $association->stp_indicator = $this->association->CIF_stp_indicator;

      if($association->fails_validation)
      {
        return false;
      }

      return $association->save();

    }
}
