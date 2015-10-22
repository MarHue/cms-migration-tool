<?php
return [
    'disks' => [
        'cms' => [
            'migrations' => [
                'input' => ['driver' => 'local', 'root' => storage_path('cms-migrations/_input')],
                'output' => ['driver' => 'local', 'root' => storage_path('cms-migrations/_output')],
                'custom_input' => ['driver' => 'local', 'root' => ''],
                'custom_output' => ['driver' => 'local', 'root' => ''],
            ]
        ]
    ]
];