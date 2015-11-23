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
class Hash extends HashBase
{
    private $_array = [];

    public function _toArray()
    {
        return $this->_array;
    }

    protected function _hasVal($key)
    {
        if (array_key_exists($key, $this->_array))
        {
            return true;
        }
        return false;
    }

    protected function _setVal($k, $v)
    {
        $this->_array[$k] = $v;
    }

    protected function &_getVal($k)
    {
        return $this->_array[$k];
    }

    protected function _delVal($k, $v)
    {
        unset($this->_array[$k]);
    }

    public function getOne ($keys, $default = null)
    {
        foreach($keys as $k)
        {
            if ($this->hasVal($k))
            {
                return $this->getVal($k);
            }
        }
        return $default;
    }
}
