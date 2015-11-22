<?php
use Nora\System\Context\Context;

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
                            function($log) {
                                $log['hoge'] = 'huga';
                            },
                            function($log) {
                                $log['ip'] = Context::singleton()->getRemoteIP();
                                $log['ua'] = Context::singleton()->getUserAgent();
                                $log['posix_user'] = Context::singleton()->getPosixUser();
                            }
                        ]
                    ]
                ]
            ]
        ]
    ]
];

