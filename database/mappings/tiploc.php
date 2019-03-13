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
        "tiploc201810201"
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
                'crs' => [ 
                    'type' => 'keyword'
                ],
                'description' => [ 
                    'type' => 'text'
                ],
                'location' => [ 
                    'type' => 'geo_point'
                ],
                'nalco' => [ 
                    'type' => 'keyword'
                ],
                'name' => [ 
                    'type' => 'text'
                ],
                'stanox' => [ 
                    'type' => 'keyword'
                ],
            ],
        ],
    ],

];