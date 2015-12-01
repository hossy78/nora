<?php
namespace Nora\System\Validation\Rule;

use Nora\System\Validation\Rule\Base\RuleIF;
use Nora\System\Validation\Rule\Exception\RuleException;

class OffsetRule extends Base\RelatedRule implements RuleIF
{
    private $_key;

    public function __construct($key, RuleIF $rule, $messages = [])
    {
        $this->_key = $key;
        $this->setName($key);

        $rule->setParam('key', $key);

        $rule->setMessages($messages);

        parent::__construct($rule);
    }

    public function assert($input, &$out)
    {
        try
        {
            $this->getRelated()->assert(
                $input[$this->_key],
                $out[$this->_key]
            );
        }
        catch(RuleException $e)
        {
            throw $this
                ->reportError($input)
                ->addRelated($e);
        }

    }
}
