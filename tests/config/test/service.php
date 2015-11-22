<?php

return [
    'all' => [
        'logger' => [
            'class' => 'Nora\System\Logging\Logger\Logger',
            'method' => 'build',
            'config' => [
                'name' => 'NoraTest',
                'handlers' => [
                    [
                        'type'  => 'stream',
                        'path'  => 'php://stdout',
                        'level' => 'info',
                        'processer' => [
                            'Nora\System\Logging\Logger\Processer\AddInfoProcesser'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'dev' => [
        'mysql' => [
            'class' => 'PDO',
            'params' => [
                'dsn' => 'mysql:dbname=test;host=127.0.0.1'
            ]
        ]
    ]
];

