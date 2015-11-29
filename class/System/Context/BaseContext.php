<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Context;

use Nora\System\Service\Provider as ServiceProvider;
use Nora\Util\Hash\Hash;
use Nora\Util\Hash\HashDataTrait;
use Nora\Util\Hash\HashIF;

class BaseContext implements HashIF
{
    use HashDataTrait;

    private $_parent;

    public function __construct (BaseContext $context = null)
    {
        if ($context != null)
        {
            $this->_parent = $context;
        }


        $this->initValues([],
            Hash::OPT_ALLOW_UNDEFINED_KEY_GET|
            Hash::OPT_ALLOW_UNDEFINED_KEY_SET
        );
    }

    public function hasParent( )
    {
        return !!$this->_parent;
    }

    public function getParent( )
    {
        return $this->_parent;
    }


    public function getServiceProvider( )
    {
        if ($this->_service_provider === null)
        {
            $this->_service_provider = new ServiceProvider();
        }
        return $this->_service_provider;
    }

    public function setService($name, $spec)
    {
        return $this->getServiceProvider()->set($name, $spec);
    }

    public function getService($name)
    {
        if (!$this->getServiceProvider()->has($name) && $this->hasParent())
        {
            return $this->getParent()->getService($name);
        }
        return $this->getServiceProvider()->get($name);
    }

    public function injectionCall($spec, $args = [])
    {
        if (is_array($spec) && is_callable($spec[count($spec)-1]))
        {
            $func = array_pop($spec);
            foreach($spec as $v) {
                array_unshift($args, $this->getService($v));
            }
            return call_user_func_array($func, $args);
        }
        return call_user_func_array($spec, $args);
    }

}
