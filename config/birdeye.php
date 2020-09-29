<?php

return [
    'api_url' => env('BIRDEYE_API_URL','https://api.birdeye.com/resources'),
    'deets' => [
        'api_key' => env('BIRDEYE_API_KEY', ''),
        'parent_business_id' => env('BIRDEYE_PARENT_BUSINESS_KEY', '') // Leave blank if using multiple accounts
    ],
    'db_connection' => 'mysql',
    'accounts' => [],
    'class_maps' => [],
];
