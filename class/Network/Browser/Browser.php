<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\Network\Browser;

use Nora\Util\Hash\Hash;
use Nora\Network\HTTP\Client\Client;
use Nora\Network\HTTP\Client\Result;
use Nora\System\Context\Context;

/**
 * Browser
 */
class Browser
{
    const UA_CHROME36='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36';
    const UA_CHROME=self::UA_CHROME36;
    const UA_DEFAULT=self::UA_CHROME;

    private $_client;
    private $_results = [];
    private $_cookie;

    static public function create($userAgent=self::UA_DEFAULT)
    {
        $client = new Client();
        $client->setOpt('USERAGENT', $userAgent);

        $browser = new Browser($client);

        return $browser;
    }

    public function __construct($client)
    {
        $this->_client = $client;
        $this->_cookie = Context::singleton()->tempFile("Browser-Cookie-");
        $this->_client->setOpt('COOKIEJAR', $this->_cookie);
        $this->_client->setOpt('COOKIEFILE', $this->_cookie);
    }

    public function __destruct( )
    {
        unlink($this->_cookie);
    }

    protected function client()
    {
        return $this->_client;
    }

    public function get($url, $params = [])
    {
        $this->pushResult(
            $this->client()->get($url, $params)
        );

        return $this;
    }
    private function pushResult(Result $res)
    {
        array_unshift($this->_results,$res);
    }

    public function getResult( )
    {
        return current($this->_results);
    }

    public function getResults( )
    {
        return $this->_results;
    }

    public function getCookie( )
    {
        return file_get_contents($this->_cookie);
    }
}
