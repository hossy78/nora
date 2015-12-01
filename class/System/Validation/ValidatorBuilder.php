<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Validation;

use Nora\System\Engine\Engine;
use Nora\System\Context\Context;
use Nora\System\Service\Provider as ServiceProvider;

if (defined('NORA_APPNAME')) define('NORA_APPNAME', 'Nora');

/**
 * Validator Builder
 */
class ValidatorBuilder
{
    static $_singleton;

    public function __construct( )
    {
        $this->_factory = new Factory();
    }

    public function validator()
    {
        return new Validator($this->_factory);
    }

    static public function start ( )
    {
        return self::singleton()->validator();
    }

    static public function singleton( )
    {
        if (!self::$_singleton)
        { 
           self::$_singleton = new static();
        }

        return self::$_singleton;
    }

    public function __call($name, $params)
    {
        return call_user_func_array(
            [$this->validator(),$name],
            $params
        );
    }
}
