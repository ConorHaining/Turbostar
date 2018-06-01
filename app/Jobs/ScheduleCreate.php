<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\ScheduleModel;
use App\LocationRecord;
use App\TiplocModel;

class ScheduleCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $schedule;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $locationRecordsRaw = $this->schedule->schedule_segment->schedule_location;

        $locationRecords = [];

        foreach ($locationRecordsRaw as $recordRaw) {

          switch ($recordRaw->location_type) {
            case 'LO':

            $record = LocationRecord::create([
              'tiploc_instance' => $recordRaw->tiploc_instance,
              'departure' => $recordRaw->departure,
              'public_departure' => $recordRaw->public_departure,
              'platform' => $recordRaw->platform,
              'line' => $recordRaw->line,
              'engineering_allowance' => $recordRaw->engineering_allowance,
              'pathing_allowance' => $recordRaw->pathing_allowance,
            ]);

              break;
            case 'LI':

            $record = LocationRecord::create([
              'tiploc_instance' => $recordRaw->tiploc_instance,
              'arrival' => $recordRaw->arrival,
              'departure' => $recordRaw->departure,
              'pass' => $recordRaw->pass,
              'public_arrival' => $recordRaw->public_arrival,
              'public_departure' => $recordRaw->public_departure,
              'platform' => $recordRaw->platform,
              'line' => $recordRaw->line,
              'path' => $recordRaw->path,
              'engineering_allowance' => $recordRaw->engineering_allowance,
              'pathing_allowance' => $recordRaw->pathing_allowance,
            ]);

              break;
            case 'LT':

            $record = LocationRecord::create([
              'tiploc_instance' => $recordRaw->tiploc_instance,
              'arrival' => $recordRaw->arrival,
              'public_arrival' => $recordRaw->public_arrival,
              'platform' => $recordRaw->platform,
              'path' => $recordRaw->path,
            ]);

              break;

          }


          $record->type = $recordRaw->location_type;

          $location = TiplocModel::where('code', $recordRaw->tiploc_code)->get();

          if(empty($location))
          {
            throw new \Exception("Error Processing Request", 1);

          }

          array_push($locationRecords, $record);

        }

        $schedule = ScheduleModel::create([
          'uid' => $this->schedule->CIF_train_uid,
          'start_date' => $this->schedule->schedule_start_date,
          'end_start' => $this->schedule->schedule_end_date,
          'signalling_id' => $this->schedule->schedule_segment->signalling_id,
          'headcode' => $this->schedule->schedule_segment->CIF_headcode,
          'course_indicator' => $this->schedule->schedule_segment->CIF_course_indicator,
          'train_service_code' => $this->schedule->schedule_segment->CIF_train_service_code,
          'portion_id' => $this->schedule->schedule_segment->CIF_business_sector,
          'speed' => $this->schedule->schedule_segment->CIF_speed,
          'connection_indicator' => $this->schedule->schedule_segment->CIF_connection_indicator,
          'traction_class' => $this->schedule->new_schedule_segment->traction_class,
          'uic_code' => $this->schedule->new_schedule_segment->uic_code,
          'location_records' => $locationRecords,
        ]);

        $schedule->running_days = $this->schedule->schedule_days_runs;
        if($schedule->fails_validation) { echo "running_days"; return false;}
        $schedule->bank_holiday_running = $this->schedule->CIF_bank_holiday_running;
        if($schedule->fails_validation) { echo "bank_holiday_running"; return false;}
        $schedule->train_status = $this->schedule->train_status;
        if($schedule->fails_validation) { echo "train_status"; return false;}
        $schedule->train_category = $this->schedule->schedule_segment->CIF_train_category;
        if($schedule->fails_validation) { echo "train_category"; return false;}
        $schedule->power_type = $this->schedule->schedule_segment->CIF_power_type;
        if($schedule->fails_validation) { echo "power_type"; return false;}
        $schedule->timing_load = $this->schedule->schedule_segment->CIF_timing_load;
        if($schedule->fails_validation) { echo "timing_load"; return false;}
        $schedule->operating_characteristics = $this->schedule->schedule_segment->CIF_operating_characteristics;
        if($schedule->fails_validation) { echo "operating_characteristics"; return false;}
        $schedule->train_class = $this->schedule->schedule_segment->CIF_train_class;
        if($schedule->fails_validation) { echo "train_class"; return false;}
        $schedule->sleepers = $this->schedule->schedule_segment->CIF_sleepers;
        if($schedule->fails_validation) { echo "sleepers"; return false;}
        $schedule->reservations = $this->schedule->schedule_segment->CIF_reservations;
        if($schedule->fails_validation) { echo "reservations"; return false;}
        $schedule->catering_code = $this->schedule->schedule_segment->CIF_catering_code;
        if($schedule->fails_validation) { echo "catering_code"; return false;}
        $schedule->service_branding = $this->schedule->schedule_segment->CIF_service_branding;
        if($schedule->fails_validation) { echo "service_branding"; return false;}
        $schedule->stp_indicator = $this->schedule->CIF_stp_indicator;
        if($schedule->fails_validation) { echo "stp_indicator"; return false;}
        $schedule->atoc_code = $this->schedule->atoc_code;
        if($schedule->fails_validation) { echo "atoc_code"; return false;}
        $schedule->applicable_timetable = $this->schedule->applicable_timetable;
        if($schedule->fails_validation) { echo "applicable_timetable"; return false;}

        return $schedule->save();
    }
}
