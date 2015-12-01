<?php
namespace Nora\System\Validation\Rule\Exception;

use Nora\System\Validation\Rule\Base\RuleIF;
use Nora\Util\Hash\HashDataTrait;
use Nora\Util\Hash\Hash;


class RuleException extends \Nora\Exception\Exception
{
    use HashDataTrait;

    private $_rule;
    private $_input;
    private $_info;
    private $_related = false;
    private $_children = false;

    static public function create(RuleIF $rule, $input, $info)
    {
        return new static($rule, $input, $info);
    }

    public function __construct($rule, $input, $info)
    {
        $this->_rule = $rule;
        $this->_input = $input;
        $this->_info = $info;

        $this->initValues([], Hash::OPT_ALLOW_UNDEFINED_KEY_SET);
    }

    protected function getRule( )
    {
        return $this->_rule;
    }

    protected function getInfo( )
    {
        return $this->_info;
    }

    protected function getInput( )
    {
        return $this->_input;
    }

    protected function getRelated( )
    {
        return $this->_related;
    }

    protected function getChildren( )
    {
        return $this->_children;
    }


    public function addRelated(RuleException $e)
    {
        $this->_related = $e;
        return $this;
    }

    public function addChildren($array)
    {
        foreach($array as $child)
        {
            $this->addChild($child);
        }
        return $this;
    }

    public function addChild(RuleException $e)
    {
        $this->_children[] = $e;
        return $this;
    }

    protected function toString($ident = 1, $name = '')
    {
        $lines = [];
        //$lines[] = get_class($this->getRule());

        if ($this->getRule()->hasName()) {
            $name  = ($name ? $name.'.': '').$this->getRule()->getName();
            $lines[] = $name;
        }

        if (false !== $children = $this->getChildren())
        {
            foreach($children as $child)
            {
                $lines[] = $child->toString($ident+1, $name);
            }
        }
        elseif(false !== $related = $this->getRelated())
        {
            $lines[] = $related->toString($ident+1, $name);
        }
        else
        {
            $lines[] = 'Message: '.$this->getRule()->getMessage($this->getInfo(), $this->getInput());
            $lines[] = 'Input: '.\Nora\Nora::dump($this->getInput(), true);
        }


        $text = '';
        foreach($lines as $line)
        {
            if ($line[0] === ">")
            {
                $text.= $line.PHP_EOL;
            }else{
                $text.= str_repeat(">", $ident).($ident != 0 ?' ':'').$line.PHP_EOL;
            }
        }
        return $text;
    }

    public function __toString( )
    {
        $text = '';
        $text.= PHP_EOL;
        $text.= '=============================';
        $text.= PHP_EOL;
        $text.= ' ERROR REPORTING';
        $text.= PHP_EOL;
        $text.= PHP_EOL;
        $text.= '=============================';
        $text.= PHP_EOL;
        $num = count($this->getChildren());
        $numRules = count($this->getRule());
        $text.= "Rule(err/pass): $num/$numRules";

        return $text.PHP_EOL.$this->toString().parent::__toString();
    }
}
