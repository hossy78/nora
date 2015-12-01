<?php
namespace Nora\System\Validation\Rule\Base;

abstract class RuleSingle extends Rule implements RuleIF
{
    public function assert($input, &$out)
    {
        if (true !== $res = $this->check($input))
        {
            throw $this->reportError($input, $res);
        }
    }

    protected function check($input)
    {
        if (empty($input)) return true;

        return $this->_check($input);
    }

    abstract protected function _check($input);
}
