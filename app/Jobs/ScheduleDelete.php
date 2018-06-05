<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


use App\ScheduleModel;
class ScheduleDelete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $schedule;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->schedule = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $expiredSchedule = ScheduleModel::where('uid', $this->schedule->CIF_train_uid)
                                          ->where('start_date', $this->schedule->schedule_start_date)
                                          ->where('stp_indicator', $this->schedule->CIF_stp_indicator);

        return $expiredSchedule->delete();
    }
}
