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
 * Curl
 */
class Curl
{
    private $_ch = null;

    private static $map =
        [
            Client::AUTOREFERER     => CURLOPT_AUTOREFERER,
            Client::REFERER         => CURLOPT_REFERER,
            Client::RETURNTRANSFER  => CURLOPT_RETURNTRANSFER,
            Client::TIMEOUT         => CURLOPT_TIMEOUT,
            Client::HTTPHEADER      => CURLOPT_HTTPHEADER,
            Client::HEADER          => CURLOPT_HEADER,
            Client::HEADER_OUT      => CURLINFO_HEADER_OUT,
            Client::HEADERFUNCTION  => CURLOPT_HEADERFUNCTION,
            Client::SSL_VERIFYPEER  => CURLOPT_SSL_VERIFYPEER,
            Client::SSL_VERIFYHOST  => CURLOPT_SSL_VERIFYHOST,
            Client::URL             => CURLOPT_URL,
            Client::FOLLOWLOCATION  => CURLOPT_FOLLOWLOCATION,
            Client::MAXREDIRS       => CURLOPT_MAXREDIRS,
            Client::USERAGENT       => CURLOPT_USERAGENT,
            Client::COOKIE          => CURLOPT_COOKIE,
            Client::COOKIEJAR       => CURLOPT_COOKIEJAR,
            Client::COOKIEFILE      => CURLOPT_COOKIEFILE,
            Client::POST            => CURLOPT_POST,
            Client::POSTFIELDS      => CURLOPT_POSTFIELDS,
            Client::VERBOSE         => CURLOPT_VERBOSE,
            Client::CUSTOMREQUEST   => CURLOPT_CUSTOMREQUEST,
            Client::HTTPAUTH        => CURLOPT_HTTPAUTH,
            Client::USERPWD         => CURLOPT_USERPWD,
            Client::PROXY           => CURLOPT_PROXY,
            Client::PROXYPORT       => CURLOPT_PROXYPORT,
            Client::HTTPPROXYTUNNEL => CURLOPT_HTTPPROXYTUNNEL,
            Client::PROXYUSERPWD    => CURLOPT_PROXYUSERPWD,
            Client::PROXYTYPE       => CURLOPT_PROXYTYPE,
        ];

    protected function handler() {

        if ($this->_ch === null)
        {
            $this->_ch = curl_init();
        }
        return $this->_ch;
    }

    public function setOpt($name, $value = null)
    {
        if (is_array($name))
        {
            foreach($name as $k=>$v)
            {
                $this->setOpt($k, $v);
            }

            return $this;
        }

        if (is_string($name))
        {
            $num = self::$map[Client::getOptToInt($name)];
        }else{
            $num = $name;
        }

        curl_setopt(
            $this->handler(),
            $num,
            $value
        );
    }

    public function execute( )
    {
        $res = Result::create(
            curl_exec($this->handler()),
            curl_getinfo($this->handler())
        );
        curl_close($this->handler());
        return $res;
    }
}
