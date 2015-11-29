<?php
namespace Nora\Util\Hash;

trait HashDataTrait
{
    private $_data;

    public function initValues($data, $opt = Hash::OPT_DEFAULT)
    {
        $this->_data = Hash::create([], $opt);
    }

    protected function data( )
    {
        return $this->_data;
    }

    public function setVal($key, $val = null)
    {
        return $this->data()->setVal($key, $val);
    }
    public function &getVal($key, $default = null)
    {
        return $this->data()->getVal($key, $default);
    }
    public function delVal($key)
    {
        return $this->data()->getVal($key, $default);
    }
    public function hasVal($key, &$found_key = null)
    {
        return $this->data()->hasVal($key, $found_key);
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
