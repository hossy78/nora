<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Logging;

use Nora\Util\Hash\Hash;

/**
 * ログ
 */
class Log implements \ArrayAccess
{
    private $_level;
    private $_message;
    private $_context;
    private $_tags;

    static public function create ($level, $name, $message)
    {
        $log = new Log;
        $log->_level = $level;
        $log->_name = $name;
        $log->_message = $message;
        $log->_time = time();
        return $log;
    }

    public function getLevel()
    {
        return $this->_level;
    }

    public function getMessage()
    {
        return $this->_message;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getTime()
    {
        return $this->_time;
    }

    public function offsetExists($k)
    {
        return array_key_exists($k, $this->_message);
    }

    public function offsetSet($k, $v)
    {
        return $this->_message[$k] = $v;
    }

    public function offsetGet($k)
    {
        return $this->_message[$k];
    }

    public function offsetUnset($k)
    {
        unset($this->_message[$k]);
    }
}
