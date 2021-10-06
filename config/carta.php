<?php

return [
    'link_email' => [
        'development' => 'http://webdev.vivere.co.id/vservice/api/email/send',
        'production'  => 'https://apps.vivere.co.id/vservice/api/email/send'
    ],
    'link_gdrive' => [
        'development' => [
            'upload' => 'http://webdev.vivere.co.id/vservice/api/google/upload_to_server',
            'create_folder' => 'http://webdev.vivere.co.id/vservice/api/google/create_folder'
        ],
        'production' => [
            'upload' => 'https://apps.vivere.co.id/vservice/api/google/upload_to_server',
            'create_folder' => 'https://apps.vivere.co.id/vservice/api/google/create_folder'
        ]
    ],
    'sales_org' => ['1000', '2000', '3000', '4000', '5000', '6000', '7000']
];