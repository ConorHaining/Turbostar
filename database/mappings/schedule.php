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
    "aliases" => [
        "schedule201810101"
    ],

    'settings' => [
        "number_of_shards" => 1,
        "number_of_replicas" => 0,
    ],

    'mappings' => [
        'schedule' => [
            'properties' => [
                'applicable_timetable' => [ 
                    'type' => 'keyword' 
                ],
                'atoc_code' => [ 
                    'type' => 'keyword' 
                ],
                'bank_holiday_running' => [ 
                    'type' => 'keyword' 
                ],
                'catering_code' => [ 
                    'type' => 'keyword' 
                ],
                'connection_indicator' => [ 
                    'type' => 'keyword' 
                ],
                'course_indicator' => [ 
                    'type' => 'keyword' 
                ],
                'end_start' => [ 
                    'type' => 'date' 
                ],
                'headcode' => [ 
                    'type' => 'keyword' 
                ],
                'location_records' => [
                    'type' => 'nested',
                    'properties' => [
                        'arrival' => [ 
                            'type' => 'date', 
                            'format' => 'HH:mm:ss', 
                        ],
                        'departure' => [ 
                            'type' => 'date', 
                            'format' => 'HH:mm:ss', 
                        ],
                        'engineering_allowance' => [ 
                            'type' => 'keyword' 
                        ],
                        'line' => [ 
                            'type' => 'keyword' 
                        ],
                        'location' => [
                            'type' => 'nested',
                            'properties' => [
                                'code' => [ 'type' => 'keyword' ],
                                'crs' => [ 'type' => 'keyword' ],
                                'description' => [ 'type' => 'text' ],
                                'location' => [ 'type' => 'geo_point' ],
                                'nalco' => [ 'type' => 'keyword' ],
                                'name' => [ 'type' => 'text' ],
                                'stanox' => [ 'type' => 'keyword' ],
                            ]
                        ],
                        'pass' => [ 
                            'type' => 'date', 
                            'format' => 'HH:mm:ss', 
                        ],
                        'path' => [ 
                            'type' => 'keyword' 
                        ],
                        'path' => [ 
                            'type' => 'keyword' 
                        ],
                        'pathing_allowance' => [ 
                            'type' => 'keyword' 
                        ],
                        'platform' => [ 
                            'type' => 'keyword' 
                        ],
                        'public_arrival' => [ 
                            'type' => 'date', 
                            'format' => 'HH:mm:ss', 
                        ],
                        'public_departure' => [ 
                            'type' => 'date', 
                            'format' => 'HH:mm:ss', 
                        ],
                        'tiploc' => [ 
                            'type' => 'keyword' 
                        ],
                        'type' => [ 
                            'type' => 'keyword' 
                        ],
                    ]
                ],
                'operating_characteristics' => [ 
                    'type' => 'keyword' 
                ],
                'portion_id' => [ 
                    'type' => 'keyword' 
                ],
                'power_type' => [ 
                    'type' => 'keyword' 
                ],
                'reservations' => [ 
                    'type' => 'keyword' 
                ],
                'running_days' => [ 
                    'type' => 'keyword' 
                ],
                'service_branding' => [ 
                    'type' => 'keyword' 
                ],
                'signalling_id' => [ 
                    'type' => 'keyword' 
                ],
                'sleepers' => [ 
                    'type' => 'keyword' 
                ],
                'speed' => [ 
                    'type' => 'integer' 
                ],
                'start_date' => [ 
                    'type' => 'date' 
                ],
                'stp_indicator' => [ 
                    'type' => 'keyword' 
                ],
                'timing_load' => [ 
                    'type' => 'keyword' 
                ],
                'traction_class' => [ 
                    'type' => 'keyword' 
                ],
                'train_category' => [ 
                    'type' => 'keyword' 
                ],
                'train_class' => [ 
                    'type' => 'keyword' 
                ],
                'train_service_code' => [ 
                    'type' => 'keyword' 
                ],
                'train_status' => [ 
                    'type' => 'keyword' 
                ],
                'uic_code' => [ 
                    'type' => 'keyword' 
                ],
                'uid' => [ 
                    'type' => 'keyword' 
                ],
            ]
        ]
    ]
];