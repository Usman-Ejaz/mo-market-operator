<?php

return [
    'date_format' => 'd/m/Y',
    'time_format' => 'H:i A',
    'datetime_placeholder' => 'DD/MM/YYYY hh:mm',
    'datetime_format' => 'd/m/Y H:i A',
    'themes' => [
        'theme1' => 'Theme 1',
        'theme2' => 'Theme 2',
        'theme3' => 'Theme 3'
    ],
    "createPassowrdLinkExpiryTime" => 30, // in mins
    
    "maxImageSize" => 2000,     // 2 MB
    "maxDocumentSize" => 5000,   // 5 MB
    "image_file_extensions" => "jpg|jpeg|png|ico|bmp",

    "client_app_base_url" => env('CLIENT_BASE_URL'),

    "storage_disk_base_path" => config('filesystems.disks.app.root') . '/',

    "google_credentials" => storage_path('app/ismo-cppa-app-google-analytics-credentials.json'),
    "ga_4_property" => "317113865",
    "ga_view_id" => "267956530"
];
