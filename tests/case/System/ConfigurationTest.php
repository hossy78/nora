<?php
use Nora\System\Configuration\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    public function testMain()
    {
        $cf = new Configuration('test');

        $cf->write('hoge', 'fuga');
        $this->assertEquals( $cf->read('hoge'), 'fuga');

        $cf->write('a.b.c', 'aaaa');

        $this->assertTrue( is_array($cf->read('a.b')) );
        $this->assertEquals( $cf->read('a.b.c'), 'aaaa');

        $cf->loadFile(TEST_DIR.'/config/load_test.php');
        $cf->loadDir(TEST_DIR.'/config/test', null, ['all', 'dev']);

        $spec = $cf->read('service.logger');
        $logger = call_user_func($spec['class'].'::'.$spec['method'], $spec['config']);
        $logger->info('ちゃんとロガーが起動できました');

        $cf->save();
    }

    public function testBuild( )
    {
        $c = Configuration::build('hoge', TEST_DIR.'/config/test', ['all','dev','hoge']);
        $this->assertEquals($c->read('database.connection.default.user'),'hoge');
    }
}

# vim:set ft=php.phpunit :
