<?php
namespace Nora\System\Validation\Rule;

class IntRule extends Base\RuleSingle implements Base\RuleIF
{
    public function __construct()
    {
        $this->setName('int');

        $this->setMessage(
            'DEFAULT', 
            'not int was given'
        );
    }

    public function assert($input, &$out)
    {
        parent::assert($input, $out);
        $out = $input;
    }

    protected function _check($input)
    {
        if (is_numeric($input))
        {
            return true;
        }
        if (is_int($input))
        {
            return true;
        }
        return false;
    }
}
