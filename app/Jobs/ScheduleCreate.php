<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Schedule;
use App\LocationRecord;
use App\Models\Tiploc;

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
      $schedule = new Schedule();

      if(!$this->schedule->CIF_stp_indicator == 'C'){
        $locationRecordsRaw = $this->schedule->schedule_segment->schedule_location;

        $locationRecords = [];

        foreach ($locationRecordsRaw as $recordRaw) {

          $record = new LocationRecord();
          
          switch ($recordRaw->location_type) {
            case 'LO': 

              $record->tiploc_instance = $recordRaw->tiploc_instance;
              $record->departure = $recordRaw->departure;
              $record->public_departure = $recordRaw->public_departure;
              $record->platform = $recordRaw->platform;
              $record->line = $recordRaw->line;
              $record->engineering_allowance = $recordRaw->engineering_allowance;
              $record->pathing_allowance = $recordRaw->pathing_allowance;

              break;
            case 'LI':

              $record->tiploc_instance = $recordRaw->tiploc_instance;
              $record->arrival = $recordRaw->arrival;
              $record->departure = $recordRaw->departure;
              $record->pass = $recordRaw->pass;
              $record->public_arrival = $recordRaw->public_arrival;
              $record->public_departure = $recordRaw->public_departure;
              $record->platform = $recordRaw->platform;
              $record->line = $recordRaw->line;
              $record->path = $recordRaw->path;
              $record->engineering_allowance = $recordRaw->engineering_allowance;
              $record->pathing_allowance = $recordRaw->pathing_allowance;

              break;
            case 'LT':

              $record->tiploc_instance = $recordRaw->tiploc_instance;
              $record->arrival = $recordRaw->arrival;
              $record->public_arrival = $recordRaw->public_arrival;
              $record->platform = $recordRaw->platform;
              $record->path = $recordRaw->path;
              
              break;
              
            }
            
            
            $record->type = $recordRaw->location_type;
            
            array_push($locationRecords, $record->toArray());
            $schedule->location_records = $locationRecords;

            $schedule->traction_class = $this->schedule->new_schedule_segment->traction_class;
            $schedule->uic_code = $this->schedule->new_schedule_segment->uic_code;
            $schedule->portion_id = $this->schedule->schedule_segment->CIF_business_sector;

            $schedule->atoc_code = $this->schedule->atoc_code;
            if($schedule->fails_validation) { echo 'fails atoc_code'; var_dump($this->schedule); fail();}
            $schedule->applicable_timetable = $this->schedule->applicable_timetable;
            if($schedule->fails_validation) { echo 'fails applicable_timetable'; var_dump($this->schedule); fail();}

        }

      }

        $schedule->uid = $this->schedule->CIF_train_uid;
        $schedule->start_date = $this->schedule->schedule_start_date;
        $schedule->end_start = $this->schedule->schedule_end_date;
        $schedule->signalling_id = $this->schedule->schedule_segment->signalling_id;
        $schedule->headcode = $this->schedule->schedule_segment->CIF_headcode;
        $schedule->course_indicator = $this->schedule->schedule_segment->CIF_course_indicator;
        $schedule->train_service_code = $this->schedule->schedule_segment->CIF_train_service_code;
        $schedule->speed = $this->schedule->schedule_segment->CIF_speed;
        $schedule->connection_indicator = $this->schedule->schedule_segment->CIF_connection_indicator;

        $schedule->running_days = $this->schedule->schedule_days_runs;
        if($schedule->fails_validation) { echo 'fails running_days'; var_dump($this->schedule); fail();}
        $schedule->bank_holiday_running = $this->schedule->CIF_bank_holiday_running;
        if($schedule->fails_validation) { echo 'fails bank_holiday_running'; var_dump($this->schedule); fail();}
        $schedule->train_status = $this->schedule->train_status;
        if($schedule->fails_validation) { echo 'fails train_status'; var_dump($this->schedule); fail();}
        $schedule->train_category = $this->schedule->schedule_segment->CIF_train_category;
        if($schedule->fails_validation) { echo 'fails train_category'; var_dump($this->schedule); fail();}
        $schedule->power_type = $this->schedule->schedule_segment->CIF_power_type;
        if($schedule->fails_validation) { echo 'fails power_type'; var_dump($this->schedule); fail();}
        $schedule->timing_load = $this->schedule->schedule_segment->CIF_timing_load;
        if($schedule->fails_validation) { echo 'fails timing_load'; var_dump($this->schedule); fail();}
        $schedule->operating_characteristics = $this->schedule->schedule_segment->CIF_operating_characteristics;
        if($schedule->fails_validation) { echo 'fails operating_characteristics'; var_dump($this->schedule); fail();}
        $schedule->train_class = $this->schedule->schedule_segment->CIF_train_class;
        if($schedule->fails_validation) { echo 'fails train_class'; var_dump($this->schedule); fail();}
        $schedule->sleepers = $this->schedule->schedule_segment->CIF_sleepers;
        if($schedule->fails_validation) { echo 'fails sleepers'; var_dump($this->schedule); fail();}
        $schedule->reservations = $this->schedule->schedule_segment->CIF_reservations;
        if($schedule->fails_validation) { echo 'fails reservations'; var_dump($this->schedule); fail();}
        $schedule->catering_code = $this->schedule->schedule_segment->CIF_catering_code;
        if($schedule->fails_validation) { echo 'fails catering_code'; var_dump($this->schedule); fail();}
        $schedule->service_branding = $this->schedule->schedule_segment->CIF_service_branding;
        if($schedule->fails_validation) { echo 'fails service_branding'; var_dump($this->schedule); fail();}
        $schedule->stp_indicator = $this->schedule->CIF_stp_indicator;
        if($schedule->fails_validation) { echo 'fails stp_indicator'; var_dump($this->schedule); fail();}

        return $schedule->save();
    }
}
