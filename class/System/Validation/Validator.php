<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Validation;

use Nora\System\Engine\Engine;
use Nora\System\Context\Context;
use Nora\System\Service\Provider as ServiceProvider;
use Nora\Util\Factory\FactoryException;

use Nora\System\Validation\Rule\Exception\RuleException;
use countable;

if (defined('NORA_APPNAME')) define('NORA_APPNAME', 'Nora');

/**
 * Validator Builder
 */
class Validator extends Rule\Base\AllOfRule implements Rule\Base\RuleIF,countable
{
    private $_factory;
    private $_rules = [];

    public static function __callStatic ($name, $params)
    {
        $v = new static();
        $v->__call($name, $params);
        return $v;
    }

    public function setParam($k, $v)
    {
        foreach($this->_rules as $rule) $rule->setParam($k, $v);
    }

    public function __construct (Factory $factory = null)
    {
        if (is_null($factory))
        {
            $factory = new Factory();
        }
        
        $this->_factory = $factory;
    }

    public function  __call($name, $params)
    {
        $rule = $this->buildRule($name, $params);
        $this->addRule($rule);
        return $this;
    }
    
    protected function addRule(Rule\Base\RuleIF $rule)
    {
        $this->_rules[] = $rule;
    }

    public function buildRule($ruleSpec, $arguments)
    {
        try {
            return $this->_factory->create($ruleSpec, $arguments);
        } catch (FactoryException $e) {
            throw new Exception\Exception($e->getMessage());
        } 
    }

    public function assert($input, &$out = [])
    {
        $exceptions = [];

        foreach($this->_rules as $rule)
        {
            try
            {
                $rule->assert($input, $out);
            }
            catch (RuleException $e)
            {
                $exceptions[] = $e;
            }
        }

        if (!empty($exceptions))
        {
            throw $this
                ->reportError($input)
                ->addChildren($exceptions);
        }
    }

    public function count( )
    {
        return count($this->_rules);
    }

    public function setMessages($m)
    {
        foreach($this->_rules as $r)
        {
            $k = strtoupper($r->getName());
            if (isset($m[$k]))
            {
                $r->setMessages($m[$k]);
            }
        }
    }

}
