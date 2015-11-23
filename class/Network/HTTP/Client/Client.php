<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\Network\HTTP\Client;

use Nora\Util\Hash\Hash;
use Nora\Network\HTTP\Client\Curl;

/**
 * Client
 */
class Client
{
    const AUTOREFERER     = 100;
    const REFERER         = 200;
    const RETURNTRANSFER  = 1;
    const TIMEOUT         = 10;
    const HTTPHEADER      = 3;
    const HEADER          = 4;
    const HEADER_OUT      = 410;
    const HEADERFUNCTION  = 420;
    const SSL_VERIFYPEER  = 5;
    const SSL_VERIFYHOST  = 6;
    const URL             = 7;
    const FOLLOWLOCATION  = 8;
    const MAXREDIRS       = 81;
    const USERAGENT       = 9;
    const COOKIE          = 10;
    const COOKIEJAR       = 11;
    const COOKIEFILE      = 12;
    const POST            = 20;
    const POSTFIELDS      = 21;
    const VERBOSE         = 99;
    const CUSTOMREQUEST   = 98;
    const HTTPAUTH        = 80;
    const USERPWD         = 81;
    const AUTH_BASIC      = CURLAUTH_BASIC;
    const PROXY           = 1004;
    const PROXYPORT       = 59;
    const HTTPPROXYTUNNEL = 61;
    const PROXYUSERPWD    = 1006;
    const PROXYTYPE       = 101;

    private $_options = [];
    private $_headers = [];

    public function __construct( )
    {
        $this->setOpt([
            'RETURNTRANSFER' => 1,
            'TIMEOUT'        => 2,
            'FOLLOWLOCATION' => 1,
            'HEADER'         => 1,
            'SSL_VERIFYPEER' => false,
            'SSL_VERIFYHOST' => 0,
            'HEADER_OUT'     => true,
            'AUTOREFERER'    => true,
            'HEADER'         => 1,
            'USERAGENT'      => 'Nora HTTP User Agent',
        ]);
    }


    static public function getOptToInt($name)
    {
        return constant('self::'.strtoupper($name));
    }

    /**
     * Get Request
     */
    public function get($url, $datas = [], $headers = [])
    {
        if (!empty($datas))
        {
            if (is_string($datas))
            {
                $url.='?'.$datas;
            }elseif(is_array($datas)){
                $url.='?'.http_build_query($datas);
            }
        }

        $curl = new Curl();
        $curl->setOpt($this->getOpts([
            'URL' => $url
        ]));
        return $curl->execute();
    }


    public function setOpt($name, $value = null)
    {
        if (is_array($name))
        {
            foreach($name as $k=>$v) $this->setOpt($k, $v);
            return $this;
        }
        $this->_options[strtoupper($name)] = $value;
        return $this;
    }

    private function getOpts($array)
    {
        return array_merge($this->_options, $array);
    }

    private function getHeaders($array)
    {
        return array_merge($this->_headers, $array);
    }
}
