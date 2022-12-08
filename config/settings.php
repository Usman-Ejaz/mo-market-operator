<?php

return [
    'datetime_placeholder' => 'DD/MM/YYYY hh:mm',
    'datetime_format' => 'd/m/Y H:i',
    'createdat_datetime_format' => 'd/m/Y g:i A',
    'themes' => [
        'theme1' => 'Theme 1',
        'theme2' => 'Theme 2',
        'theme3' => 'Theme 3'
    ],
    "createPassowrdLinkExpiryTime" => 30, // in mins

    "maxImageSize" => 25000,     // 2 MB
    "maxDocumentSize" => 25000,   // 5 MB
    "image_file_extensions" => "jpg|jpeg|png|ico|bmp",

    "client_app_base_url" => env('CLIENT_BASE_URL'),

    "storage_disk" => "app",

    "storage_disk_base_path" => config('filesystems.disks.app.root') . '/',

    "client_removal_threshold" => 20,   // remove clients if their profile is incomplete. threshold is in days.

    "google_credentials" => storage_path('app/mo-cppa-app-google-analytics-credentials.json'),
    "ga_4_property" => "317113865",
    "ga_view_id" => "267956530"
];
