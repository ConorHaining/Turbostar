<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\AssociationModel;
use App\ScheduleModel;
use App\TiplocModel;

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
      $mainTrain = ScheduleModel::where('uid', $this->association->main_train_uid)->get();
      $assocTrain = ScheduleModel::where('uid', $this->association->assoc_train_uid)->get();
      $location = TiplocModel::where('code', $this->association->location)->get();

      $expiredAssociation = AssociationModel::where('start_date', $this->association->assoc_start_date)
                                        ->where('base_location_suffix', $this->association->base_location_suffix)
                                        ->where('stp_indicator', $this->association->CIF_stp_indicator);

      /**
       * According to the Laravel documentation, the delete() function should return a bool value
       * depending on the success of the model being deleted.
       *
       * However, the package which supports Mongodb returns an int value. I believe it's due to
       * this line: https://github.com/jenssegers/laravel-mongodb/blob/master/src/Jenssegers/Mongodb/Eloquent/Builder.php#L92
       */
      return $expiredAssociation->delete();
    }
}
