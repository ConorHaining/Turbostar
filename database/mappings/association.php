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
        "association201810201"
    ],

    'settings' => [
        "number_of_shards" => 1,
        "number_of_replicas" => 0,
    ],

    'mappings' => [
        'association' => [
            "properties" => [
                'assoc_location_suffix' => [ 
                    'type' => 'keyword' 
                ],
                'assoc_train' => [ 
                    'type' => 'keyword' 
                ],
                'base_location_suffix' => [ 
                    'type' => 'keyword' 
                ],
                'category' => [ 
                    'type' => 'keyword' 
                ],
                'date_indicator' => [ 
                    'type' => 'keyword' 
                ],
                'end_date' => [ 
                    'type' => 'date' 
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
                'main_train' => [ 
                    'type' => 'keyword' 
                ],
                'running_days' => [ 
                    'type' => 'keyword' 
                ],
                'start_date' => [ 
                    'type' => 'date' 
                ],
                'stp_indicator' => [ 
                    'type' => 'keyword' 
                ],
            ]
        ]
    ]

],