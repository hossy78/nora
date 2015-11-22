<?php
use Nora\Nora;

class Test extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    public function testMain()
    {
        // セットアップ
        Nora::Configure(TEST_DIR, 'dev', [
            'config' => 'config/test',
        ]);

        Nora::getService('logger')->err('エラーだよ');

        $this->assertEquals(
            spl_object_hash(Nora::getService('logger')),
            spl_object_hash(Nora::getService('logger'))
        );


        // 既存クラスをサービスにする
        Nora::setService('mysql', [
            'class' => 'PDO',
            'params' => [
                'dsn' => 'mysql:dbname=test;host=127.0.0.1'
            ]
        ]);

        // サービスを読み込む
        Nora::setService('hoge', [
            'callback' => function ($db) {
                return $db;
            },
            'params' => [
                'db' => '@mysql'
            ]
        ]);

        var_Dump(Nora::getService('hoge')->prepare('SHOW TABLES;')->fetch());

    }
}

# vim:set ft=php.phpunit :
