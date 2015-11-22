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

use Nora\Util\Hash\Hash;
use Nora\Util\Browser\UserAgent;

/**
 * コンテクスト
 */
class Context
{
    private $_client  = false;
    private $_server  = false;
    private $_data = [];
    private $_cache_path = '/tmp';

    public $_ENV;
    public $_GET;
    public $_POST;

    static private $_singleton = false;

    // コンテクストをビルドする
    static public function init ( )
    {
        $context = new static();

        $context->_ENV  = Hash::create($_ENV,Hash::OPT_ALLOW_ALL|Hash::OPT_IGNORE_CASE);
        $context->_GET  = Hash::create($_GET,Hash::OPT_ALLOW_ALL|Hash::OPT_IGNORE_CASE);
        $context->_POST = Hash::create($_POST,Hash::OPT_ALLOW_ALL|Hash::OPT_IGNORE_CASE);
        $context->_data = Hash::create([
            'cache' => '/tmp'
        ],Hash::OPT_ALLOW_ALL|Hash::OPT_IGNORE_CASE);

        return $context;
    }

    static public function singleton( )
    {
        if (self::$_singleton === false)
        {
            self::$_singleton = self::init();
        }
        return self::$_singleton;
    }

    /**
     * ユーザーエージェントを取得する
     */
    public function getUserAgent( )
    {
        return UserAgent::create(
            $this->_ENV->getVal('HTTP_USER_AGENT', 'cli')
        );
    }

    /**
     * リモートIPを取得する
     */
    public function getRemoteIP( )
    {
        return $this->_ENV->getOne([
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ], '127.0.0.1');
    }

    /**
     * ホスト名を取得
     */
    public function getHostname( )
    {
        return getHostName();
    }

    /**
     * Posix User
     */
    public function getPosixUser( )
    {
        return posix_getpwuid(posix_getuid())['name'];
    }

    /**
     * キャッシュパスを取得する
     */
    public function getCachePath()
    {
        $data = func_get_args();
        $path = implode("/", $data);
        return $this->_cache_path.'/'.$path;
    }

    /**
     * キャッシュパスを設定
     */
    public function setCachePath($path)
    {
        $this->_cache_path = $path;
    }

    public function set($name, $value = false)
    {
        $this->_data->setVal($name, $value);
    }

    public function get($name, $value = null)
    {
        return $this->_data->getVal($name, $value);
    }
}
