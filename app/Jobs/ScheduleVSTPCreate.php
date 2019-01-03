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

class ScheduleVSTPCreate implements ShouldQueue
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
        $this->schedule = $schedule->schedule;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(strtolower($this->schedule->transaction_type) == "delete") {

            $expiredSchedule = Schedule::where('uid', 'like', $this->schedule->CIF_train_uid)
                                          ->where('start_date', 'like', $this->schedule->schedule_start_date)
                                          ->where('stp_indicator', 'like', $this->schedule->CIF_stp_indicator)
                                          ->first();
            $expiredSchedule->delete();                              

        } else if(strtolower($this->schedule->transaction_type) == "create") {

            $schedule = new Schedule();
    
            if($this->schedule->CIF_stp_indicator != 'C') {
                $schedule = $this->createLocationRecords($schedule);
            }
    
            $schedule->uid = $this->schedule->CIF_train_uid;
            $schedule->start_date = $this->schedule->schedule_start_date;
            $schedule->end_start = $this->schedule->schedule_end_date;
            $schedule->signalling_id = $this->schedule->schedule_segment[0]->signalling_id;
            $schedule->headcode = $this->schedule->schedule_segment[0]->CIF_headcode;
            $schedule->course_indicator = $this->schedule->schedule_segment[0]->CIF_course_indicator;
            $schedule->train_service_code = $this->schedule->schedule_segment[0]->CIF_train_service_code;
            $schedule->speed = intval($this->schedule->schedule_segment[0]->CIF_speed) / 2.24;
            $schedule->connection_indicator = $this->schedule->schedule_segment[0]->CIF_connection_indicator;
    
            $schedule->running_days = $this->schedule->schedule_days_runs;
            $schedule->bank_holiday_running = $this->schedule->CIF_bank_holiday_running;
            $schedule->train_status = $this->schedule->train_status;
            $schedule->train_category = $this->schedule->schedule_segment[0]->CIF_train_category;
            $schedule->power_type = $this->schedule->schedule_segment[0]->CIF_power_type;
            $schedule->timing_load = $this->schedule->schedule_segment[0]->CIF_timing_load;
            $schedule->operating_characteristics = $this->schedule->schedule_segment[0]->CIF_operating_characteristics;
            $schedule->train_class = $this->schedule->schedule_segment[0]->CIF_train_class;
            $schedule->sleepers = $this->schedule->schedule_segment[0]->CIF_sleepers;
            $schedule->reservations = $this->schedule->schedule_segment[0]->CIF_reservations;
            $schedule->catering_code = $this->schedule->schedule_segment[0]->CIF_catering_code;
            $schedule->service_branding = $this->schedule->schedule_segment[0]->CIF_service_branding;
            $schedule->stp_indicator = $this->schedule->CIF_stp_indicator;
            
            return $schedule->save();
        }

    }

    /**
     * Format location record times into an ES suitable format
     * 
     * @param  string
     * @return string, null | In the HH:mm:ss format
     */
    public function formatTime($time)
    {
        if(empty(trim($time))) {
            return null;
        }

        $hours =  substr($time, 0, 2);
        $minutes = substr($time, 2, 2);
        $seconds = substr($time, 4, 2);
      
        $timestamp = $hours . ":" . $minutes . ":" . $seconds;
        return $timestamp;
    }

    /**
     * 
     */
    public function createLocationRecords(Schedule $schedule)
    {
        $locationRecordsRaw = $this->schedule->schedule_segment[0]->schedule_location;

        $locationRecords = [];

        foreach ($locationRecordsRaw as $recordRaw) {

            $record = new LocationRecord();
        
            $tiplocDocument = Tiploc::where('code', $recordRaw->location->tiploc->tiploc_id)
                                    ->get()->toArray();
            unset($tiplocDocument['_index']);
            unset($tiplocDocument['_type']);
            unset($tiplocDocument['_id']);
            unset($tiplocDocument['_score']);

            $record->arrival = $this->formatTime($recordRaw->scheduled_arrival_time);
            $record->departure = $this->formatTime($recordRaw->scheduled_departure_time);
            $record->pass = $this->formatTime($recordRaw->scheduled_pass_time);
            $record->public_arrival = $this->formatTime($recordRaw->public_arrival_time);
            $record->public_departure = $this->formatTime($recordRaw->public_departure_time);
            $record->platform = $recordRaw->CIF_platform;
            $record->line = $recordRaw->CIF_line;
            $record->path = $recordRaw->CIF_path;
            $record->engineering_allowance = $recordRaw->CIF_engineering_allowance;
            $record->pathing_allowance = $recordRaw->CIF_pathing_allowance;
            $record->location = $tiplocDocument;
        
            array_push($locationRecords, $record->toArray());
            $schedule->location_records = $locationRecords;

        }

        $schedule->traction_class = $this->schedule->schedule_segment[0]->CIF_traction_class;
        $schedule->uic_code = $this->schedule->schedule_segment[0]->uic_code;
        $schedule->portion_id = $this->schedule->schedule_segment[0]->CIF_business_sector;

        $schedule->atoc_code = $this->schedule->schedule_segment[0]->atoc_code;
        $schedule->applicable_timetable = $this->schedule->applicable_timetable;
        return $schedule;

    }
}
