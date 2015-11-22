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
    private $_config;
    private $_root;

    public function __construct ( )
    {
    }

    public function Context( )
    {
        return $this->getService('context');
    }

    public function Config( )
    {
        return $this->_config;
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
    public function configure ($name, $root, $env, $options)
    {
        $this->_root = realpath($root);

        $this->Context()->set($options);

        // CachePathを設定
        $this->Context()->setCachePath(
            $this->getFilePath(
                $this->Context()->get('cache', '/tmp/cache')
            )
        );

        // ConfigPathを設定
        $configPath = $this->getFilePath(
            $this->Context()->get('config', 'config')
        );

        // Configを作成
        $this->_config = Configuration::build($name, $configPath, $env, $useCache);

        return $this;
    }

    /**
     * ファイルを取得する
     */
    public function getFilePath( )
    {
        return $this->_root.'/'.implode('/', func_get_args());
    }
}
