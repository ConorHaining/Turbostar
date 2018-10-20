<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Elasticsearch Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the Elasticsearch connections below you wish
    | to use as your default connection for all work. Of course.
    |
    */

    'default' => env('ELASTIC_CONNECTION', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Elasticsearch Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the Elasticsearch connections setup for your application.
    | Of course, examples of configuring each Elasticsearch platform.
    |
    */

    'connections' => [

        'default' => [

            'servers' => [

                [
                    "host" => env("ELASTIC_HOST", "127.0.0.1"),
                    "port" => env("ELASTIC_PORT", 9200),
                    'user' => env('ELASTIC_USER', ''),
                    'pass' => env('ELASTIC_PASS', ''),
                    'scheme' => env('ELASTIC_SCHEME', 'http'),
                ]

            ],

            'index' => env('ELASTIC_INDEX', 'my_index'),

            // Elasticsearch handlers
            // 'handler' => new MyCustomHandler(),
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Elasticsearch Indices
    |--------------------------------------------------------------------------
    |
    | Here you can define your indices, with separate settings and mappings.
    | Edit settings and mappings and run 'php artisan es:index:update' to update
    | indices on elasticsearch server.
    |
    | 'my_index' is just for test. Replace it with a real index name.
    |
    */

    'indices' => [

        'tiploc' => require(database_path('mappings/tiploc.php')),

        'schedule' => require(database_path('mappings/schedule.php')),
        
        'association' => require_once(database_path('mappings/association.php')),

        'movement' => [
            
            'aliases' => [
                'movement_feed'
            ],

            'settings' => [
                "number_of_shards" => 1,
                "number_of_replicas" => 0,
            ],

            'mappings' => [
                'movement' => [
                    'properties' => [
                        'train_uid' => [
                            'type' => 'keyword'
                        ],
                        'message_type' => [
                            'type' => 'keyword'
                        ],
                        'start_date' => [
                            'type' => 'date',
                        ],
                        'end_date' => [
                            'type' => 'date',
                        ],
                        'schedule_source' => [
                            'type' => 'keyword'
                        ],
                        'creation_timestamp' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'actual_origin_stanox' => [
                            'type' => 'keyword'
                        ],
                        'd1266_record_number' => [
                            'type' => 'keyword'
                        ],
                        'call_type' => [
                            'type' => 'keyword'
                        ],
                        'call_mode' => [
                            'type' => 'keyword'
                        ],
                        'schedule_type' => [
                            'type' => 'keyword'
                        ],
                        'schedule_origin_stanox' => [
                            'type' => 'keyword'
                        ],
                        'schedule_wtt_id' => [
                            'type' => 'keyword'
                        ],
                        'origin_location_stanox' => [
                            'type' => 'keyword'
                        ],
                        'departure_time' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'cancelled_at' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'cancel_reason_code' => [
                            'type' => 'keyword'
                        ],
                        'origin_location_timestamp' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'cancel_type' => [
                            'type' => 'keyword'
                        ],
                        'original_location_stanox' => [
                            'type' => 'keyword'
                        ],
                        'planned_timestamp' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'timetable_variation' => [
                            'type' => 'keyword'
                        ],
                        'delay_monitoring_point' => [
                            'type' => 'keyword'
                        ],
                        'next_report_run_time' => [
                            'type' => 'keyword'
                        ],
                        'stanox' => [
                            'type' => 'keyword'
                        ],
                        'actual_timestamp' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'correction_indicator' => [
                            'type' => 'boolean'
                        ],
                        'event_source' => [
                            'type' => 'keyword'
                        ],
                        'terminated' => [
                            'type' => 'boolean'
                        ],
                        'offroute' => [
                            'type' => 'boolean'
                        ],
                        'variation_status' => [
                            'type' => 'keyword'
                        ],
                        'report_expected' => [
                            'type' => 'keyword'
                        ],
                        'direction_indication' => [
                            'type' => 'keyword'
                        ],
                        'route' => [
                            'type' => 'keyword'
                        ],
                        'next_report_stanox' => [
                            'type' => 'keyword'
                        ],
                        'line_indicator' => [
                            'type' => 'keyword'
                        ],
                        'event_type' => [
                            'type' => 'keyword'
                        ],
                        'platform' => [
                            'type' => 'keyword'
                        ],
                        'original_loc_timestamp' => [
                            'type' => 'keyword'
                        ],
                        'reinstatement_timestamp' => [
                            'type' => 'keyword'
                        ],
                        'toc_id' => [
                            'type' => 'keyword'
                        ],
                        'reason_code' => [
                            'type' => 'keyword'
                        ],
                        'origin_change_timestamp' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'revised_train_id' => [
                            'type' => 'keyword'
                        ],
                        'train_id' => [
                            'type' => 'keyword'
                        ],
                        'data_source' => [
                            'type' => 'keyword'
                        ],
                        'nr_queue_timestamp' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'original_location_timestamp' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'current_train_id' => [
                            'type' => 'keyword'
                        ],
                        'departure_timestamp' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'location_stanox' => [
                            'type' => 'keyword'
                        ],
                        'event_timestamp' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'received_at' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'processed_at' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                    ],
                ],
            ],
        ],
    ]

];
