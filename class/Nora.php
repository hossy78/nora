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
use Nora\System\Context\Context;
use Nora\System\Service\Provider as ServiceProvider;

if (defined('NORA_APPNAME')) define('NORA_APPNAME', 'Nora');

/**
 * Nora
 */
class Nora
{
    static private $_engine = null;
    static public $cnt = 0;

    static public function __callStatic($name, $params)
    {
        return call_user_func_array([self::getEngine(), $name], $params);
    }

    static public function getEngine( )
    {
        if (self::$_engine === null)
        {
            self::$_engine = new Engine(Context::singleton());

            // Engineのセットアップ
            //self::$_engine->setServiceProvider(new ServiceProvider());

            // サービスの組み込み
            self::$_engine->setService('context', self::Context());
        }
        return self::$_engine;
    }

    static public function configure($config, $env, $option = [])
    {
        self::$_engine = null;

        if (!is_array($env)) $env = [$env];
        array_unshift($env,'all');

        $user = self::Context()->getPosixUser();
        self::getEngine()->configure(NORA_APP_NAME.".".$user, $config, $env, $option);
        self::setService('config', self::getEngine()->Config());


        $config = self::getService('config');

        // PHPの設定
        mb_language($config->read('lang', 'ja'));
        mb_internal_encoding($config->read('encoding', 'utf-8'));

        // Timelimit
        set_time_limit($config->read('time_limit', 20));

        // サービスの設定
        if ($config->has('service'))
        {
            foreach($config->read('service') as $k=>$v)
            {
                self::setService($k, $v);
            }
        }

        // オートロード
        if ($config->has('map.class'))
        {
            // ノラクラスを読み込む
            self::setService(
                'autoloader',
                AutoLoader::register($config->read('map.class'))
            );
        }

        // 出力ハンドラを仕込む
        self::setService(
            'output',
            [
                'class' => 'Nora\System\IO\Writer\Writer',
                'method' => 'create',
                'params' => [
                    '@context'
                ]
            ]
        );

        // デバッガを仕込む
        self::setService(
            'debugger', 
            [
                'class' => 'Nora\Develop\Debug\Debugger',
                'params' => [
                    '@context'
                ]
            ]
        );

        // バリデータを仕込む
        self::setService(
            'validator',
            [
                'class' => 'Nora\System\Validation\ValidatorBuilder',
            ]
        );

        // 多言語対応
        self::setService(
            'translator',
            [
                'class' => 'Nora\I18n\Translator'
            ]
        );

        // モジュールビルダー
        self::setService(
            'module',
            [
                'class' => 'Nora\System\Module\ModuleBuilder',
                'params' => [
                    '@context'
                ]
            ]
        );

    }

    static public function message($tpl, $params)
    {
        return self::getService(
            'translator'
        )->message($tpl, $params);
    }

    static public function dump($var, $return = false, $options = [])
    {
        return \Nora\Develop\Debug\Dumper::dump($var, $return, $options);
    }

    static public function help($var)
    {
        $help = new \Nora\Develop\Document\Help(self::Context());
        return $help($var);
    }
}
