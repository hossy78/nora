<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Web;

use Nora\System\Context\Context as SystemContext;
use Nora\Nora;
use Nora\System\Routing\RequestIF;

/**
 * Web Request
 */
class Request implements RequestIF
{
    private $_uri;
    private $_method;
    private $_matched;
    private $_data = [];

    static public function createRequest(Context $context)
    {
        $req = new self();
        $req->_uri = $context->requestUri();
        $req->_method = $context->requestMethod();

        if ($req->_method === 'GET')
        {
            $req->_data = $context->_GET; 
        }else{
            $req->_data = $context->_POST;
        }
        return $req;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * マスクしたパスを取得する
     *
     * @param string $mask
     * @return string
     */
    public function getMaskedPath($mask)
    {
        $path = $this->_uri->path();
        if (false !== strpos($path, $mask))
        {
            $path = substr($path, strlen($mask));
        }
        if (empty($path)) $path = '/';

        return $path;
    }


    /**
     * @NoHelp
     */
    public function setMatched($matched)
    {
        $this->_matched = $matched;
    }

    public function  __toString( )
    {
        return $this->_method.' '.$this->_uri;
    }
}

