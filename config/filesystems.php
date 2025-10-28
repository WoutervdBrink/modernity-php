<?php

return [
    'default' => 'app',

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],
        'reports' => [
            'driver' => 'local',
            'root' => storage_path('app/reports'),
        ],
    ],
];
