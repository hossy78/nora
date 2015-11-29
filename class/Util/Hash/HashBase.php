<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\Util\Hash;


/**
 * ハッシュクラス
 * ====================
 */
class HashBase implements HashIF
{
    const OPT_IGNORE_CASE = 1;
    const OPT_ALLOW_UNDEFINED_KEY_SET = 2;
    const OPT_ALLOW_UNDEFINED_KEY_GET = 4;
    const OPT_ALLOW_UNDEFINED_KEY = 6;
    const OPT_FULL = 7;
    const OPT_SECURE = self::OPT_FULL^self::OPT_ALLOW_UNDEFINED_KEY_GET;
    const OPT_ALLOW_ALL = self::OPT_ALLOW_UNDEFINED_KEY_SET|self::OPT_ALLOW_UNDEFINED_KEY_GET;
    const OPT_DEFAULT = self::OPT_SECURE^self::OPT_IGNORE_CASE;
    const OPT_STRICT = 0;

    const ERROR_SET_ON_UNDEFINED_KEY=1;
    const ERROR_GET_ON_UNDEFINED_KEY=1;

    private $_option;

    public static function create ($data = [], $option=self::OPT_DEFAULT)
    {
        $class = get_called_class();
        $hash = new $class($data, $option);
        return $hash;
    }

    protected function __construct ($data, $option)
    {
        if (empty($data)) $data = [];

        $this->_option = $option;

        $this->initValues($data, $option);
    }

    public function toArray()
    {
        return $this->_toArray();
    }


    public function initValues($data, $opt = self::OPT_DEFAULT)
    {
        foreach($data as $k=>$v)
        {
            $this->_setVal($k, $v);
        }
        return $this;
    }

    public function setVal($key, $val = null)
    {
        if (is_array($key))
        {
            foreach($key as $k=>$v) $this->setVal($k, $v);
            return $this;
        }

        if (!($this->_option & self::OPT_ALLOW_UNDEFINED_KEY_SET) && !$this->hasVal($key))
        {
            $this->raizeError(self::ERROR_SET_ON_UNDEFINED_KEY, "Undefined Key: $key");
            return false;
        }

        $this->_setVal($key, $val);
        return $this;
    }

    public function &getVal($key, $default = null)
    {
        if (!$this->hasVal($key) && !($this->_option & self::OPT_ALLOW_UNDEFINED_KEY_GET))
        {
            $this->raizeError(self::ERROR_GET_ON_UNDEFINED_KEY, "Undefined Key: $key");
            return $default;
        }

        if ($this->hasVal($key, $found_key))
        {
            return $this->_getVal($found_key);
        }

        return $default;
    }


    public function delVal($key)
    {
        if ($this->hasVal($key, $found_key))
        {
            return $this->_delVal($found_key);
        }

        return false;
    }

    public function hasVal($key, &$found_key = null)
    {
        if ($this->_option & self::OPT_IGNORE_CASE)
        {
            foreach($this->toArray() as $k=>$v)
            {
                if (0===strcasecmp($k, $key))
                {
                    $found_key = $k;
                    return true;
                }
            }
            return false;
        }

        $found_key = $key;
        return $this->_hasVal($key);
    }

    protected function raizeError($code, $message)
    {
        switch ($code)
        {
        case self::ERROR_SET_ON_UNDEFINED_KEY:
            throw new Exception\HashSetOnUndefinedKey($message);
            break;

        case self::ERROR_GET_ON_UNDEFINED_KEY:
            throw new Exception\HashSetOnUndefinedKey($message);
            break;

        default:
            throw new Exception\HashException($message);
            break;
        }
    }

    # for count {{{
    public function count ( )
    {
        return count ($this->toArray());
    }

    # for ArrayAccess {{{
    #
    public function &offsetGet($key)
    {
        return $this->getVal($key);
    }

    public function offsetSet($key, $value)
    {
        return $this->setVal($key, $value);
    }

    public function offsetExists($key)
    {
        return $this->hasVal($key);
    }
    public function offsetUnset($key)
    {
        return $this->delVal($key);
    }


    # for Magic Methods {{{

    public function __isset($key)
    {
        return $this->hasVal($key);
    }

    public function &__get($key)
    {
        return $this->getVal($key);
    }

    public function __set($key, $value)
    {
        return $this->setVal($key, $value);
    }

    # }}}

    # for IteratorAggregate {{{
    public function getIterator( )
    {
        foreach($this->toArray() as $k=>$v)
        {
            yield $k=>$v;
        }
    }

    # }}
}
