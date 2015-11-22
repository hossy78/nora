<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora;

use Nora\System\Engine\Engine;
use Nora\System\Service\Provider as ServiceProvider;

if (defined('NORA_APPNAME')) define('NORA_APPNAME', 'Nora');

/**
 * Nora
 */
class Nora
{
    static private $_engine = null;

    static public function __callStatic($name, $params)
    {
        return call_user_func_array([self::getEngine(), $name], $params);
    }

    static public function getEngine( )
    {
        if (self::$_engine === null)
        {
            self::$_engine = new Engine();

            // Engineのセットアップ
            self::$_engine->setServiceProvider(new ServiceProvider());

            // サービスの組み込み
            self::$_engine->setService('context', [
                'class' => 'Nora\System\Context\Context',
                'method' => 'singleton'
            ]);
        }
        return self::$_engine;
    }

    static public function configure($config, $env, $debug = false)
    {
        self::$_engine = null;

        if (!is_array($env)) $env = [$env];
        array_unshift($env,'all');

        $useCache = $debug ? false: true;

        self::getEngine()->configure(NORA_APP_NAME, $config, $env, $useCache);

        $config = self::getService('config');

        if ($config->has('service'))
        {
            foreach($config->read('service') as $k=>$v)
            {
                self::setService($k, $v);
            }
        }
    }
}
