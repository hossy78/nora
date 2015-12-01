<?php
namespace Nora\System\Validation\Rule;

class NotEmptyRule extends Base\RuleSingle implements Base\RuleIF
{
    public function __construct()
    {
        $this->setName('notEmpty');

        $this->setMessage(
            'DEFAULT', 
            'empty was given'
        );
    }


    protected function check($input)
    {
        return $this->_check($input);
    }

    protected function _check($input)
    {
        return !empty($input) ? true: 'EMPTY';
    }
}
