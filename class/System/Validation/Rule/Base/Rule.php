<?php
namespace Nora\System\Validation\Rule\Base;

use Nora\System\Validation\Rule\Exception\RuleException;

abstract class Rule implements RuleIF
{
    private $_name = null;
    private $_messages = [
        'DEFAULT' => 'INVALID (%(code))'
    ];
    private $_params = [];

    public function __construct( )
    {
    }

    public function setParams($arr)
    {
        foreach($arr as $k=>$v)
        {
            $this->setParam($k, $v);
        }
    }
    public function setParam($k, $v)
    {
        $this->_params[$k] = $v;
    }

    public function getParam($k)
    {
        return $this->_params[$k];
    }

    public function hasParam($k)
    {
        return isset($this->_params[$k]);
    }

    public function setMessages($messages)
    {
        if (!is_array($messages))
        {
            $this->_messages = [
                'DEFAULT' => $messages
            ];
            return $this;
        }
        foreach($messages as $k=>$v)
        {
            $this->setMessage($k, $v);
        }
    }

    public function setMessage($k, $v)
    {
        $this->_messages[$k] = $v;
    }

    public function hasName()
    {
        return !empty($this->_name);
    }

    public function getName( )
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function reportError($input, $info = null)
    {
        return RuleException::create(
            $this,
            $input,
            $info
        );
    }

    public function getMessage($code, $input)
    {
        if (isset($this->_messages[$code]))
        {
            $msg = $this->_messages[$code];
        }
        else
        {
            $msg = $this->_messages['DEFAULT'];
        }

        return preg_replace_callback('/%\(([^\)]+)\)/', function($m) use ($input, $code) {

            if ($m[1] === 'input')
            {
                return trim(\Nora\Nora::dump($input, true));
            }

            if ($m[1] === 'code')
            {
                return $code;
            }

            if ($this->hasParam($m[1]))
            {
                return $this->getParam($m[1]);
            }
            return $m[0];
        }, $msg);
    }

}
