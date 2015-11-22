<?php
namespace Nora\Service\Logging;

use Nora\AutoLoader;
use Nora\System\Logging\Logging;
use Nora\System\Logging\Logger\Logger;
use Nora\System\Logging\Logger\Handler\Handler;
use Nora\System\Context\Context;

class LoggingTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    public function testLogger( )
    {
        // ロガー作成
        $logger = Logger::create('Nora');

        // $this->allMessages($logger);

    }

    public function allMessages($logger)
    {
        $logger->debug("デバッグ");
        $logger->info("インフォ");
        $logger->notice("通知");
        $logger->warning("注意");
        $logger->err("エラー");
        $logger->alert("警告");
        $logger->emerg("緊急");
    }

    public function testHandler( )
    {
        // ハンドラ作成
        $handler = Handler::create([
                'type'  => 'stream',
                'path'  => 'php://stderr',
                'level' => 'warning'
        ]);

        $logger = Logger::create('Nora');
        $logger->addHandler($handler);

        $this->allMessages($logger);
    }

    public function testBuildUp( )
    {
       $logger =  Logger::build([
            'name' => 'NoraFile',
            'handlers' => [
                [
                    'type'  => 'stream',
                    'path'  => '/tmp/nora.%(time|Y-m-d).%(user).log',
                    'level' => 'warning',
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
        ]);

       $logger->err('エラー');
    }
}
# vim:set ft=php.phpunit :
