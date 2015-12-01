<?php
namespace Nora\System\Validation\Rule;

class StringRule extends Base\RuleSingle implements Base\RuleIF
{
    private $_default,$_useStringify;

    public function __construct($default = null, $useStringify = false)
    {
        $this->_default = $default;
        $this->_useStringify = $useStringify;

        $this->setName('string');

        $this->setMessage(
            'DEFAULT', 
            'not a string was given'
        );
    }

    public function assert($input, &$out)
    {
        parent::assert($input, $out);

        if ($this->_useStringify)
        {
            $input = (string) $input;
        }

        if ($this->_default !== null && empty($input))
        {
            $input = $this->_default;
        }
        $out = $input;
    }

    protected function _check($input)
    {
        if (is_string($input))
        {
            return true;
        }
        return false;
    }
}
