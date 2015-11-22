<?php
return [
    'all' => [
        'connection' => [
            'default' => [
                'type' => 'mysql',
                'user' => 'dbuser',
                'passwd' => 'passw0rd'
            ],
            'test1' => [
                'type' => 'mysql',
                'user' => 'dbuser',
                'passwd' => 'passw0rd'
            ]
        ]
    ],
    'dev' => [
        'connection.default.user' => 'hoge',
        'connection.test1' => [
            'user' => 'hoge'
        ]
    ]
];
