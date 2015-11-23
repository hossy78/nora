<?php
use Nora\Network\Browser\Browser;

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
        $b = Browser::create()
            ->get('http://localhost')
            ->get('http://localhost')
            ->get('http://localhost')
            ->get('http://localhost')
            ->get('http://localhost')
            ->get('http://localhost')
            ;

        // 結果
        foreach($b->getResults() as $r)
        {
            echo $r->header();
            echo strip_tags($r->body());
        }

        // クッキー
        var_Dump($b->getCookie());

    }
}

# vim:set ft=php.phpunit :
