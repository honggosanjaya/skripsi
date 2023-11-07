<?php

return [
    'name' => env('MANIFEST', 'salesman'),
    'manifest' => [
        'name' => env('MANIFEST', 'salesman'),
        'short_name' => env('MANIFEST', 'salesman'),
        'start_url' => env('APP_URL',''),
        'background_color' => '#ffffff',
        'theme_color' => 'rgb(2, 121, 247)',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black',
        'icons' => [
          '512x512' => [
            'path' => '/images/icons/icon-perusahaan.png',
            'purpose' => 'any'
          ],
        ],
        'splash' => [
          '512x512' => '/images/icons/icon-perusahaan.png',
        ],
        'custom' => []
    ]
];
