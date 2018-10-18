<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

use App\Models\Movement;

class MovementCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var JSON 
     */
    private $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (strval($this->payload->header->msg_type) == Movement::ACTIVATION) {
            $movement = $this->activation($this->payload);
        } else if(strval($this->payload->header->msg_type) == Movement::CANCELLATION) {
            $movement = $this->cancellation($this->payload);
        } else if(strval($this->payload->header->msg_type) == Movement::MOVEMENT) {
            $movement = $this->movement($this->payload);
        } else if(strval($this->payload->header->msg_type) == Movement::UNIDENTIFIED) {
            $movement = $this->unidentified($this->payload);
        } else if(strval($this->payload->header->msg_type) == Movement::REINSTATEMENT) {
            $movement = $this->reinstatement($this->payload);
        } else if(strval($this->payload->header->msg_type) == Movement::ORIGINCHANGE) {
            $movement = $this->originChange($this->payload);
        } else if(strval($this->payload->header->msg_type) == Movement::IDENTITYCHANGE) {
            $movement = $this->identityChange($this->payload);
        } else if(strval($this->payload->header->msg_type) == Movement::LOCATIONCHANGE) {
            $movement = $this->locationChange($this->payload);
        } else {
            Log::error('Unknown Message Type', ['message_id' => $this->payload->header->msg_type]);
        }

        $movement->save();
    }

    /**
     * Process an activation message
     */
    public function activation($payload) {

        $movement = new Movement();
        $movement->message_type = $payload->header->msg_type;

        $movement->train_uid = $payload->body->train_uid;
        $movement->train_id = $payload->body->train_id;
        $movement->data_source = $payload->header->original_data_source;
        $movement->nr_queue_timestamp = intval($payload->header->msg_queue_timestamp);
        $movement->toc_id = $payload->body->toc_id;

        $movement->message_type = $payload->header->msg_type;
        $movement->start_date = $payload->body->schedule_start_date;
        $movement->end_date = $payload->body->schedule_end_date;
        $movement->schedule_source = $payload->body->schedule_source;
        $movement->creation_timestamp = $payload->body->creation_timestamp;
        $movement->actual_origin_stanox = $payload->body->tp_origin_stanox;
        $movement->departure_timestamp = $payload->body->origin_dep_timestamp;
        $movement->d1266_record_number = $payload->body->d1266_record_number;
        $movement->call_type = $payload->body->train_call_type;
        $movement->call_mode = $payload->body->train_call_mode;

        switch($payload->body->schedule_type) {
            case 'P':
            $movement->schedule_type = 'O';
            case 'O':
            $movement->schedule_type = 'P';
            default:
            $movement->schedule_type = $payload->body->schedule_type;
        }


        $movement->schedule_origin_stanox = $payload->body->sched_origin_stanox ;
        $movement->schedule_wtt_id = $payload->body->schedule_wtt_id ;

        return $movement;

    }

    /**
     * Process an cancellation message
     */
    public function cancellation($payload) {

        $movement = new Movement();
        $movement->message_type = $payload->header->msg_type;

        
        $movement->origin_location_stanox = $payload->body->orig_loc_stanox;
        $movement->departure_time = intval($payload->body->dep_timestamp);
        $movement->location_stanox = $payload->body->loc_stanox;
        $movement->cancelled_at = intval($payload->body->canx_timestamp);
        $movement->cancel_reason_code = $payload->body->canx_reason_code;
        $movement->origin_location_timestamp = intval($payload->body->orig_loc_timestamp);
        $movement->cancel_type = $payload->body->canx_type;
        
        return $movement;
        
    }

    /**
     * Process an movement message
     */
    public function movement($payload) {
        
        $movement = new Movement();
        $movement->message_type = $payload->header->msg_type;

        try {
            $movement->train_id = $payload->body->train_id;
            $movement->data_source = $payload->header->original_data_source;
            $movement->nr_queue_timestamp = intval($payload->header->msg_queue_timestamp);
            $movement->toc_id = $payload->body->toc_id;
    
            $movement->original_location_stanox = $payload->body->original_loc_stanox;
            $movement->planned_timestamp = intval($payload->body->planned_timestamp);
            $movement->timetable_variation = $payload->body->timetable_variation;
            $movement->original_location_timestamp = intval($payload->body->original_loc_timestamp);
            $movement->current_train_id = $payload->body->current_train_id;
            $movement->delay_monitoring_point = $payload->body->delay_monitoring_point;
            $movement->next_report_run_time = $payload->body->next_report_run_time;
            $movement->stanox = $payload->body->loc_stanox;
            $movement->actual_timestamp = $payload->body->actual_timestamp;
            $movement->correction_indicator = $payload->body->correction_ind;
            $movement->event_source = $payload->body->event_source;
            $movement->terminated = $payload->body->train_terminated;
            $movement->offroute = $payload->body->offroute_ind;
            $movement->variation_status = $payload->body->variation_status;
            $movement->report_expected = $payload->body->auto_expected;
            $movement->direction_indication = $payload->body->direction_ind;
            $movement->route = $payload->body->route;
            $movement->next_report_stanox = $payload->body->next_report_stanox;
            $movement->line_indicator = $payload->body->line_ind;
            $movement->event_type = $payload->body->event_type;
            $movement->platform = $payload->body->platform;
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['line' => $e->getLine(), 'payload' => $payload->body, 'timestamp' => $payload->body->planned_timestamp]);
        }

        return $movement;

    }

    /**
     * Process an unidentified message
     */
    public function unidentified($payload) {
        Log::error('Unidentified message received', ['payload' => json_encode($payload)]);
    }

    /**
     * Process an reinstatement message
     */
    public function reinstatement($payload) {

        $movement = new Movement();
        $movement->message_type = $payload->header->msg_type;

        $movement->train_id = $payload->body->train_id;
        $movement->data_source = $payload->header->original_data_source;
        $movement->nr_queue_timestamp = intval($payload->header->msg_queue_timestamp);
        $movement->toc_id = $payload->body->toc_id;
        
        $movement->current_train_id = $payload->body->current_train_id;
        $movement->original_loc_timestamp = $payload->body->original_loc_stanox;
        $movement->departure_timestamp = $payload->body->dep_timestamp;
        $movement->location_stanox = $payload->body->loc_stanox;
        $movement->original_location_stanox = $payload->body->original_loc_stanox;
        $movement->reinstatement_timestamp = $payload->body->reinstatement_timestamp;
        
        return $movement;

    }

    /**
     * Process an origin change message
     */
    public function originChange($payload) {

        $movement = new Movement();
        $movement->message_type = $payload->header->msg_type;
        
        $movement->train_id = $payload->body->train_id;
        $movement->data_source = $payload->header->original_data_source;
        $movement->nr_queue_timestamp = intval($payload->header->msg_queue_timestamp);
        $movement->toc_id = $payload->body->toc_id;

        $movement->reason_code = $payload->body->reason_code;
        $movement->current_train_id = $payload->body->current_train_id;
        $movement->departure_timestamp = $payload->body->dep_timestamp;
        $movement->origin_change_timestamp = $payload->body->coo_timestamp;
        $movement->location_stanox = $payload->body->loc_stanox;
        $movement->train_id = $payload->body->train_id;
        $movement->original_location_stanox = $payload->body->original_loc_stanox;
        
        return $movement;

    }

    /**
     * Process an identity change message
     */
    public function identityChange($payload) {

        $movement = new Movement();
        $movement->message_type = $payload->header->msg_type;
        
        $movement->train_id = $payload->body->train_id;
        $movement->data_source = $payload->header->original_data_source;
        $movement->nr_queue_timestamp = intval($payload->header->msg_queue_timestamp);

        $movement->current_train_id = $payload->body->current_train_id;
        $movement->revised_train_id = $payload->body->revised_train_id;
        $movement->train_id = $payload->body->train_id;
        $movement->event_timestamp = $payload->body->event_timestamp;

        return $movement;
    }

    /**
     * Process an Location Change message
     */
    public function locationChange($payload) {

        $movement = new Movement();
        $movement->message_type = $payload->header->msg_type;

        $movement->train_id = $payload->body->train_id;
        $movement->data_source = $payload->header->original_data_source;
        $movement->nr_queue_timestamp = intval($payload->header->msg_queue_timestamp);

        $movement->original_location_timestamp = $payload->body->original_loc_timestamp;
        $movement->current_train_id = $payload->body->current_train_id;
        $movement->departure_timestamp = $payload->body->dep_timestamp;
        $movement->location_stanox = $payload->body->loc_stanox;
        $movement->original_location_stanox = $payload->body->original_loc_stanox;
        $movement->event_timestamp = $payload->body->event_timestamp;

        return $movement;

    }
}
