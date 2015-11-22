<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Engine;

use Nora\System\Context\Context;
use Nora\System\Configuration\Configuration;
use Nora\System\Service\Provider as ServiceProvider;

/**
 * Engine
 */
class Engine
{
    private $_service_provider;

    public function __construct ( )
    {
    }

    public function Context( )
    {
        return $this->getService('context');
    }

    public function setServiceProvider(ServiceProvider $sp)
    {
        $this->_service_provider = $sp;
    }

    public function getService($name)
    {
        return $this->_service_provider->get($name);
    }

    public function setService($name, $spec)
    {
        return $this->_service_provider->set($name, $spec);
    }

    /**
     * セットアップ
     */
    public function configure ($name, $config, $env, $useCache = true)
    {
        $config = Configuration::build($name, $config, $env, $useCache);
        $this->setService('config', $config);
        return $this;
    }
}
