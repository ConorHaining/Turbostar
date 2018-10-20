<?php

return [
    
    /**
     * In the world of Elasticsearch, I am using alaiases as
     * a defacto migration tool. As such, alaiases should be in the format:
     * 
     * [mapping_name]YYYYMMDDX
     * 
     * Where X is an incredmenting number should there be more than one change in the same day.
     * This mapping name must be changed on the appropriate model at the same time
     */
    'aliases' => [
        'movement201820101'
    ],

    'settings' => [
        "number_of_shards" => 1,
        "number_of_replicas" => 0,
    ],

    'mappings' => [
        'movement' => [
            'properties' => [
                'actual_origin_stanox' => [
                    'type' => 'keyword'
                ],
                'actual_timestamp' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'call_mode' => [
                    'type' => 'keyword'
                ],
                'call_type' => [
                    'type' => 'keyword'
                ],
                'cancel_reason_code' => [
                    'type' => 'keyword'
                ],
                'cancel_type' => [
                    'type' => 'keyword'
                ],
                'cancelled_at' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'correction_indicator' => [
                    'type' => 'boolean'
                ],
                'creation_timestamp' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'current_train_id' => [
                    'type' => 'keyword'
                ],
                'd1266_record_number' => [
                    'type' => 'keyword'
                ],
                'data_source' => [
                    'type' => 'keyword'
                ],
                'delay_monitoring_point' => [
                    'type' => 'keyword'
                ],
                'departure_time' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'departure_timestamp' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'direction_indication' => [
                    'type' => 'keyword'
                ],
                'end_date' => [
                    'type' => 'date',
                ],
                'event_source' => [
                    'type' => 'keyword'
                ],
                'event_timestamp' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'event_type' => [
                    'type' => 'keyword'
                ],
                'line_indicator' => [
                    'type' => 'keyword'
                ],
                'location_stanox' => [
                    'type' => 'keyword'
                ],
                'message_type' => [
                    'type' => 'keyword'
                ],
                'next_report_run_time' => [
                    'type' => 'keyword'
                ],
                'next_report_stanox' => [
                    'type' => 'keyword'
                ],
                'nr_queue_timestamp' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'offroute' => [
                    'type' => 'boolean'
                ],
                'origin_change_timestamp' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'origin_location_stanox' => [
                    'type' => 'keyword'
                ],
                'origin_location_timestamp' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'original_loc_timestamp' => [
                    'type' => 'keyword'
                ],
                'original_location_stanox' => [
                    'type' => 'keyword'
                ],
                'original_location_timestamp' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'planned_timestamp' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'platform' => [
                    'type' => 'keyword'
                ],
                'processed_at' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'reason_code' => [
                    'type' => 'keyword'
                ],
                'received_at' => [
                    'type' => 'date', 
                    'format' => 'epoch_millis',
                ],
                'reinstatement_timestamp' => [
                    'type' => 'keyword'
                ],
                'report_expected' => [
                    'type' => 'keyword'
                ],
                'revised_train_id' => [
                    'type' => 'keyword'
                ],
                'route' => [
                    'type' => 'keyword'
                ],
                'schedule_origin_stanox' => [
                    'type' => 'keyword'
                ],
                'schedule_source' => [
                    'type' => 'keyword'
                ],
                'schedule_type' => [
                    'type' => 'keyword'
                ],
                'schedule_wtt_id' => [
                    'type' => 'keyword'
                ],
                'stanox' => [
                    'type' => 'keyword'
                ],
                'start_date' => [
                    'type' => 'date',
                ],
                'terminated' => [
                    'type' => 'boolean'
                ],
                'timetable_variation' => [
                    'type' => 'keyword'
                ],
                'toc_id' => [
                    'type' => 'keyword'
                ],
                'train_id' => [
                    'type' => 'keyword'
                ],
                'train_uid' => [
                    'type' => 'keyword'
                ],
                'variation_status' => [
                    'type' => 'keyword'
                ],
            ],
        ],
    ],
],