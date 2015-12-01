<?php
/**
 * アノテーション
 */
namespace Nora\Util\Annotation;


class Annotation
{
    public function __construct($data)
    {
        $this->_data = $data;
    }

    public function getClass( )
    {
        return $this->_data['class'];
    }

    public function getMethods( )
    {
        return $this->_data['methods'];
    }
}
