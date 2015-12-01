<?php
namespace Nora\System\Validation;

use ReflectionClass;
use Nora\Util\Factory\StandardFactory;

class Factory extends StandardFactory
{
    protected $classPrefixList = [
        ['Nora\\System\\Validation\\Rule\\', 'Rule']
    ];

    protected function checkClass(ReflectionClass $ref)
    {
        if($ref->isSubclassOf('Nora\System\Validation\Rule\Base\RuleIF'))
        {
            return true;
        }
        return false;
    }

}
