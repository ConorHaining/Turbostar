<?php

return [

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
                                'location' => [
                                    'type' => 'geo_point'
                                ]
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
                    'type' => 'keyword'
                ],
            ]
        ]
    ]
];