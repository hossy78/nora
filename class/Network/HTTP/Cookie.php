<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\Network\HTTP;

use Nora\System\Context\Context;
use Nora\Util\Hash\Hash;

/**
 * クッキー
 */
class Cookie extends Hash
{
    private $_expire   = 0;
    private $_path     = '';
    private $_domain   = '';
    private $_secure   = false;
    private $_httponly = false;

    private $_data = [];

    public function __construct($expire = 0, $path = '', $domein = '', $secure = false, $httponly = false)
    {
        $this->_expire = $expire;
        $this->_path = $path;
        $this->_domain = $domein;
        $this->_secure = $secure;
        $this->_httponly = $httponly;

        $this->initValues(
            $_COOKIE
            //$this->parse(Context::singleton()->_ENV['HTTP_COOKIE'])
        );
    }


    public function has($key)
    {
        return $this->hasVal($key);
    }

    public function get($key, $value = null)
    {
        return $this->getVal($key);
    }

    public function set($key, $value, $expire = null)
    {
        if ($expire === null) $this->_expire = $expire;
        setcookie($key, $value, time() - 60*60*24*365, $this->_path, $this->_domain, $this->_secure, $this->_httponly);
        setcookie($key, $value, $this->_expire, $this->_path, $this->_domain, $this->_secure, $this->_httponly);
    }

    public function dump( )
    {
        var_dump($_COOKIE);
    }

    public function parse($str)
    {
        $data = [];
        $datas = explode(";", $str);
        foreach($datas as $v)
        {
            list($key, $value) = explode("=", $v, 2);
            $data[trim($key)] = $value;
        }
        return $data;
    }
}
