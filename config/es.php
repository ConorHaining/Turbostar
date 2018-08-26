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

        'tiploc' => [

            "aliases" => [
                "timetableIndex"
            ],

            'settings' => [
                "number_of_shards" => 1,
                "number_of_replicas" => 0,
            ],

            'mappings' => [
                'tiploc' => [
                    "properties" => [
                        'code' => [
                            'type' => 'keyword'
                        ],
                        'nalco' => [
                            'type' => 'keyword'
                        ],
                        'stanox' => [
                            'type' => 'keyword'
                        ],
                        'crs' => [
                            'type' => 'keyword'
                        ],
                        'description' => [
                            'type' => 'text'
                        ],
                        'name' => [
                            'type' => 'text'
                        ],
                    ]
                ]
            ]

        ],

        'schedule' => [

            "aliases" => [
                "timetable"
            ],

            'settings' => [
                "number_of_shards" => 1,
                "number_of_replicas" => 0,
            ],

            'mappings' => [
                'schedule' => [
                    'properties' => [
                        'uid' => [
                            'type' => 'keyword'
                        ],
                        'start_date' => [
                            'type' => 'date'
                        ],
                        'end_start' => [
                            'type' => 'date'
                        ],
                        'signalling_id' => [
                            'type' => 'keyword'
                        ],
                        'headcode' => [
                            'type' => 'keyword'
                        ],
                        'course_indicator' => [
                            'type' => 'keyword'
                        ],
                        'train_service_code' => [
                            'type' => 'keyword'
                        ],
                        'portion_id' => [
                            'type' => 'keyword'
                        ],
                        'speed' => [
                            'type' => 'integer'
                        ],
                        'connection_indicator' => [
                            'type' => 'keyword'
                        ],
                        'traction_class' => [
                            'type' => 'keyword'
                        ],
                        'uic_code' => [
                            'type' => 'keyword'
                        ],
                        'running_days' => [
                            'type' => 'keyword'
                        ],
                        'bank_holiday_running' => [
                            'type' => 'keyword'
                        ],
                        'train_status' => [
                            'type' => 'keyword'
                        ],
                        'train_category' => [
                            'type' => 'keyword'
                        ],
                        'power_type' => [
                            'type' => 'keyword'
                        ],
                        'timing_load' => [
                            'type' => 'keyword'
                        ],
                        'operating_characteristics' => [
                            'type' => 'keyword'
                        ],
                        'train_class' => [
                            'type' => 'keyword'
                        ],
                        'sleepers' => [
                            'type' => 'keyword'
                        ],
                        'reservations' => [
                            'type' => 'keyword'
                        ],
                        'catering_code' => [
                            'type' => 'keyword'
                        ],
                        'service_branding' => [
                            'type' => 'keyword'
                        ],
                        'stp_indicator' => [
                            'type' => 'keyword'
                        ],
                        'atoc_code' => [
                            'type' => 'keyword'
                        ],
                        'applicable_timetable' => [
                            'type' => 'nested'
                        ],
                    ]
                ]
            ]
        ],
        
        'association' => [

            "aliases" => [
                "portions"
            ],

            'settings' => [
                "number_of_shards" => 1,
                "number_of_replicas" => 0,
            ],

            'mappings' => [
                'associations' => [
                    "properties" => [
                        'start_date' => [
                            'type' => 'date'
                        ],
                        'end_date' => [
                            'type' => 'date'
                        ],
                        'running_days' => [
                            'type' => 'keyword'
                        ],
                        'base_location_suffix' => [
                            'type' => 'keyword'
                        ],
                        'assoc_location_suffix' => [
                            'type' => 'keyword'
                        ],
                        'main_train' => [
                            'type' => 'keyword'
                        ],
                        'assoc_train' => [
                            'type' => 'keyword'
                        ],
                        'category' => [
                            'type' => 'keyword'
                        ],
                        'date_indicator' => [
                            'type' => 'keyword'
                        ],
                        'location' => [
                            'type' => 'keyword'
                        ],
                        'stp_indicator' => [
                            'type' => 'keyword'
                        ],
                    ]
                ]
            ]

        ],

        'headers' => [

            "aliases" => [
                "fileheaders"
            ],

            'settings' => [
                "number_of_shards" => 1,
                "number_of_replicas" => 0,
            ],

            'mappings' => [
                'tiploc' => [
                    "properties" => [
                        'date' => [
                            'type' => 'date'
                        ],
                        'sequence' => [
                            'type' => 'integer'
                        ],
                    ]
                ]
            ]

        ],

    ]

];
