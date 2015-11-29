<?php
namespace Nora\System\Web;

use Nora\Base\Component\Component;
/**
 * Web Context
 */
class URL
{
    private $_is_https = false;
    private $_host = false;
    private $_path = false;
    private $_port = false;

    static function createURL($host, $path, $port = 80, $isHttps = false)
    {
        $url = new URL( );
        $url->setHost($host);
        $url->setPath($path);
        $url->setPort($port);
        $url->isHttps($isHttps);
        return $url;
    }

    private function __construct( )
    {
    }

    private function setHost($host)
    {
        $this->_host = $host;
        return $this;
    }

    private function setPath($path)
    {
        $this->_path = $path;
        return $this;
    }

    private function setPort($port)
    {
        $this->_port = intval($port);
    }

    public function isHttps($flag = null)
    {
        if ($flag === null)
        {
            return $this->_is_https;
        }

        $this->_is_https = (bool) $flag;
        return $this;
    }

    public function buildUrl(...$path)
    {
        if (empty($path))
        {
            $path = [$this->_path];
        }
        $path = array_map(function ($v) {
            return trim($v, '/');
        }, $path);

        $path = implode('/', $path);

        return $this->scheme().'://'.
            $this->host().
            ($this->port() === 80 ? '': ':'.$this->port()).'/'.$path;
    }

    public function buildPath(...$path)
    {
        if (empty($path))
        {
            $path = [$this->_path];
        }
        $path = array_map(function ($v) {
            return trim($v, '/');
        }, $path);

        $path = implode('/', $path);

        return '/'.$path;
    }

    public function __tostring( )
    {
        return $this->buildUrl();
    }

    public function scheme( )
    {
        return ($this->isHttps() ? 'https' : 'http');
    }

    public function host()
    {
        return $this->_host;
    }

    public function port()
    {
        return $this->_port;
    }

    public function path()
    {
        return $this->_path;
    }
}
