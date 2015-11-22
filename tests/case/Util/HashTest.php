<?php
namespace Nora;

use Nora\AutoLoader;
use Nora\Util\Hash\Hash;

class AutoLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    public function testAutoLoad( )
    {
        AutoLoader::register([
            'Nora' => __DIR__.'/../../class'
        ]);

        $this->assertTrue(class_exists('Nora\Util\Hash\Hash'));
    }

    public function testHash( )
    {
        $hash = Hash::create([
            'a' => 1,
            'b' => 2,
            'C' => 3
        ], Hash::OPT_SECURE|Hash::OPT_IGNORE_CASE);

        $this->assertTrue($hash->hasVal("c"));
        $hash->setVal("xx", "4");
        $this->assertTrue($hash->hasVal("xx"));
        $this->assertEquals($hash->getVal("xX"), "4");
    }
}
# vim:set ft=php.phpunit :
