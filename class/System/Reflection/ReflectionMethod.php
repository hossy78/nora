<?php
/**
 * Nora Project
 * ============
 *
 * @since 20150618
 * @author kurari@hajime.work
 */
Namespace Nora\System\Reflection;

use ReflectionMethod as Base;

/**
 * ユーティリティファサード
 */
class ReflectionMethod extends Base
{
    use ReflectionTrait;

    /**
     * オーバーライド
     */
    public function getParameters( )
    {
        $params = [];

        foreach(parent::getParameters() as $p)
        {
            $params[] = new ReflectionParameter([
                $p->getDeclaringClass()->getName(),
                $p->getDeclaringFunction()->getName(),
            ], $p->getName());
        }
        return $params;
    }

    public function toString( )
    {
        $str = '';
        $str.= $this->isStatic() ? '::': '->';
        $str.= $this->getName();
        $str.= sprintf(" ( %s ) ", $this->toStringParams());
        return $str;
    }


    public function toStringParams()
    {
        $params = $this->getParameters();
        $param_strs = [];
        foreach($this->getParameters() as $p)
        {
            $param_strs[] = $p->toString();
        }
        return implode(", ", $param_strs);
    }
}

/* vim: set foldmethod=marker st=4 ts=4 sw=4 et: */
