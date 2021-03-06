<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Schedule;
use App\Models\LocationRecord;
use App\Models\Tiploc;
use Illuminate\Support\Facades\Log;

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

        if($this->schedule->CIF_stp_indicator != 'C') {
            $schedule = $this->createLocationRecords($schedule);
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
        $schedule->bank_holiday_running = $this->schedule->CIF_bank_holiday_running;
        $schedule->train_status = $this->schedule->train_status;
        $schedule->train_category = $this->schedule->schedule_segment->CIF_train_category;
        $schedule->power_type = $this->schedule->schedule_segment->CIF_power_type;
        $schedule->timing_load = $this->schedule->schedule_segment->CIF_timing_load;
        $schedule->operating_characteristics = $this->schedule->schedule_segment->CIF_operating_characteristics;
        $schedule->train_class = $this->schedule->schedule_segment->CIF_train_class;
        $schedule->sleepers = $this->schedule->schedule_segment->CIF_sleepers;
        $schedule->reservations = $this->schedule->schedule_segment->CIF_reservations;
        $schedule->catering_code = $this->schedule->schedule_segment->CIF_catering_code;
        $schedule->service_branding = $this->schedule->schedule_segment->CIF_service_branding;
        $schedule->stp_indicator = $this->schedule->CIF_stp_indicator;
        
        return $schedule->save();
    }

    /**
     * Format location record times into an ES suitable format
     * 
     * @param  string
     * @return string, null | In the HH:mm:ss format
     */
    public function formatTime($time)
    {
        if(empty($time)) {
            return null;
        }

        $hours =  substr($time, 0, 2);
        $minutes = substr($time, 2, 2);
      
        if (substr($time, -1) == 'H') {
            $seconds = '30';          
        } else {
            $seconds = '00';        
        }

        $timestamp = $hours . ":" . $minutes . ":" . $seconds;
        return $timestamp;
    }

    /**
     * 
     */
    public function createLocationRecords(Schedule $schedule)
    {
        $locationRecordsRaw = $this->schedule->schedule_segment->schedule_location;

        $locationRecords = [];

        foreach ($locationRecordsRaw as $recordRaw) {

            $record = new LocationRecord();
        
            $tiplocDocument = Tiploc::where('code', $recordRaw->tiploc_code)
                                    ->get()->toArray();
            unset($tiplocDocument['_index']);
            unset($tiplocDocument['_type']);
            unset($tiplocDocument['_id']);
            unset($tiplocDocument['_score']);

            switch ($recordRaw->location_type) {
            case 'LO': 

                $record->tiploc = $recordRaw->tiploc_code;
                $record->departure = $this->formatTime($recordRaw->departure);
                $record->public_departure = $this->formatTime($recordRaw->public_departure);
                $record->platform = $recordRaw->platform;
                $record->line = $recordRaw->line;
                $record->engineering_allowance = $recordRaw->engineering_allowance;
                $record->pathing_allowance = $recordRaw->pathing_allowance;
                $record->location = $tiplocDocument;

                break;
            case 'LI':

                $record->tiploc = $recordRaw->tiploc_code;
                $record->arrival = $this->formatTime($recordRaw->arrival);
                $record->departure = $this->formatTime($recordRaw->departure);
                $record->pass = $this->formatTime($recordRaw->pass);
                $record->public_arrival = $this->formatTime($recordRaw->public_arrival);
                $record->public_departure = $this->formatTime($recordRaw->public_departure);
                $record->platform = $recordRaw->platform;
                $record->line = $recordRaw->line;
                $record->path = $recordRaw->path;
                $record->engineering_allowance = $recordRaw->engineering_allowance;
                $record->pathing_allowance = $recordRaw->pathing_allowance;
                $record->location = $tiplocDocument;

                break;
            case 'LT':

                $record->tiploc = $recordRaw->tiploc_code;
                $record->arrival = $this->formatTime($recordRaw->arrival);
                $record->public_arrival = $this->formatTime($recordRaw->public_arrival);
                $record->platform = $recordRaw->platform;
                $record->path = $recordRaw->path;
                $record->location = $tiplocDocument;
            
                break;
            
            }
        
        
            $record->type = $recordRaw->location_type;
        
            array_push($locationRecords, $record->toArray());
            $schedule->location_records = $locationRecords;

        }

        $schedule->traction_class = $this->schedule->new_schedule_segment->traction_class;
        $schedule->uic_code = $this->schedule->new_schedule_segment->uic_code;
        $schedule->portion_id = $this->schedule->schedule_segment->CIF_business_sector;

        $schedule->atoc_code = $this->schedule->atoc_code;
        $schedule->applicable_timetable = $this->schedule->applicable_timetable;
        return $schedule;

    }
}
