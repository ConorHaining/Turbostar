<?php

namespace App\Models;

use Basemkhirat\Elasticsearch\Model;

/**
 * @see    https://wiki.openraildata.com/index.php/Train_Activation
 * @author Conor Haining <conor.haining@gmail.com>
 */
class Movement extends Model
{
    protected $index = 'movement';

    protected $type = 'movement';
    /**
     * Constants desribing each of the eight possible message types
     * which may appear from the NR Movement Feed.
     * 
     * @see https://wiki.openraildata.com/index.php/Train_Movements#Message_types
     */
    const ACTIVATION = '0001';
    const CANCELLATION = '0002';
    const MOVEMENT = '0003';
    const UNIDENTIFIED = '0004';
    const REINSTATEMENT = '0005';
    const ORIGINCHANGE = '0006';
    const IDENTITYCHANGE = '0007';
    const LOCATIONCHANGE = '0008';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'train_uid',
        'train_id',
        'data_source',
        'nr_queue_timestamp',
        'toc_id',

        // Activation
        'start_date',
        'end_date',
        'schedule_source',
        'creation_timestamp',
        'actual_origin_stanox',
        'departure_timestamp',
        'd1266_record_number',
        'call_type',
        'call_mode',
        'schedule_type',
        'schedule_origin_stanox',
        'schedule_wtt_id',

        // Cancellation
        'origin_location_stanox',
        'departure_time',
        'location_stanox',
        'cancelled_at',
        'cancel_reason_code',
        'origin_location_timestamp',
        'cancel_type',

        // Movement
        'original_location_stanox',
        'planned_timestamp',
        'timetable_variation',
        'original_location_timestamp',
        'current_train_uid',
        'delay_monitoring_point',
        'next_report_run_time',
        'stanox',
        'actual_timestamp',
        'correction_indicator',
        'event_source',
        'terminated',
        'offroute',
        'variation_status',
        'report_expected',
        'direction_indication',
        'route',
        'next_report_stanox',
        'line_indicator',
        
        // Reinstatement
        'reinstatement_timestamp',

        // Change of Origin
        'reason_code',
        'origin_change_timestamp',

        // Change of Identity
        'revised_train_id',

        // Change of Location
        
        // Analytics
        'queued_at',
        'processed_at',

        'message_type',
        
        // Movement
        'event_type',
        'planned_event_type',
    ];
    

    public function setMessageTypeAttribute($message_type)
    {

        $validValues = [self::ACTIVATION , self::CANCELLATION , self::MOVEMENT , self::UNIDENTIFIED , self::REINSTATEMENT , self::ORIGINCHANGE , self::IDENTITYCHANGE , self::LOCATIONCHANGE];

        if (in_array(strtoupper($message_type), $validValues)) {

            return strtoupper($message_type);

        } else {

            $this->attributes['fails_validation'] = true;

        }

    }

    public function setEventTypeAttribute($event_type)
    {

        $validValues = ['ARRIVAL', 'DEPARTURE'];

        if (in_array(strtoupper($event_type), $validValues)) {

            return strtoupper($event_type);

        } else {

            $this->attributes['fails_validation'] = true;

        }

    }

    public function setPlannedEventTypeAttribute($planned_event_type)
    {

        $validValues = ['ARRIVAL', 'DEPARTURE', 'DESTINATION'];

        if (in_array(strtoupper($planned_event_type), $validValues)) {

            return strtoupper($planned_event_type);

        } else {

            $this->attributes['fails_validation'] = true;

        }

    }
}
