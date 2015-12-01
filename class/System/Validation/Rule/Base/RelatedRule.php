<?php
namespace Nora\System\Validation\Rule\Base;

abstract class RelatedRule extends Rule implements RuleIF
{
    private $_related;

    public function __construct(RuleIF $rule)
    {
        $this->_related = $rule;
    }

    protected function getRelated( )
    {
        return $this->_related;
    }
}
