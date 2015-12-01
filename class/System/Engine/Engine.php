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

use Nora\Exception\UndefinedMethodException;
use Nora\System\Logging\Logger\Logger;
use Nora\System\Logging\ClientTrait as LoggingClientTrait;

/**
 * Engine
 */
class Engine
{
    use LoggingClientTrait;

    private $_service_provider;
    private $_config;
    private $_root;

    public function __construct (Context $context)
    {
        $this->_context = $context;
    }

    public function Context( )
    {
        // return $this->getService('context');
        return $this->_context;
    }

    public function Config( )
    {
        return $this->_config;
    }

    public function getService($name)
    {
        return $this->Context()->getService($name);
    }

    public function setService($name, $spec)
    {
        return $this->Context()->setService($name, $spec);
    }

    /**
     * セットアップ
     */
    public function configure ($name, $root, $env, $options)
    {
        $this->_root = realpath($root);
        $options['root'] = $this->_root;
        $options['env'] = $env;
        $options['name'] = $name;

        $this->Context()->setVal($options);

        // CachePathを設定
        $this->Context()->setCachePath(
            $this->getFilePath(
                $this->Context()->getVal('cache', '/tmp/cache')
            )
        );

        // ConfigPathを設定
        $configPath = $this->getFilePath(
            $this->Context()->getVal('config', 'config')
        );

        // Configを作成
        $this->_config = Configuration::build($name, $configPath, $env, $useCache = false);

        return $this;
    }

    /**
     * ファイルを取得する
     */
    public function getFilePath( )
    {
        return $this->Context()->getFilePath(implode('/', func_get_args()));
    }

    /**
     * ログをハンドルする
     */
    public function __call($name, $params)
    {
        if (false !== $this->detectLoggingCall($name, $params))
        {
            return true;
        }

        throw new UndefinedMethodException($this, $name);
    }

}
