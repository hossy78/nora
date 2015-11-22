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
class Result
{
    static public function create($res, $info)
    {
        return new Result($res, $info);
    }

    public function __construct($res, $info)
    {
        $this->_res = $res;
        $this->_info = $info;
    }

    public function header()
    {
        return substr($this->getResult(), 0, $this->headerSize());
    }

    public function body()
    {
        return substr($this->getResult(), $this->headerSize());
    }

    public function getInfo()
    {
        return $this->_info;
    }

    public function getResult()
    {
        return $this->_res;
    }

    public function code()
    {
        return $this->getInfo()['http_code'];
    }

    public function headerSize()
    {
        return $this->getInfo()['header_size'];
    }
}
