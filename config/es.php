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

    'default' => env('ELASTIC_CONNECTION'),

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

        'production' => [

            'servers' => [

                [
                    "host" => env("ELASTIC_HOST"),
                    "port" => env("ELASTIC_PORT"),
                    'user' => env('ELASTIC_USER', ''),
                    'pass' => env('ELASTIC_PASS', ''),
                    'scheme' => env('ELASTIC_SCHEME', 'https'),
                ]

            ],

            'index' => env('ELASTIC_INDEX', 'my_index'),

            // Elasticsearch handlers
            'handler' => new \Aws\ElasticsearchService\ElasticsearchPhpHandler('eu-west-1'),
        ],

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
            // 'handler' => new \Aws\ElasticsearchService\ElasticsearchPhpHandler('eu-west-1'),
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

        'tiploc' => include database_path('mappings/tiploc.php'),

        'schedule' => include database_path('mappings/schedule.php'),
        
        'association' => include_once database_path('mappings/association.php'),

        'movement' => include_once database_path('mappings/movement.php'),
    ]

];
