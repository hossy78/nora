<?php
/**
 * Nora Project
 * ============
 *
 * @since 20150618
 * @author kurari@hajime.work
 */
Namespace Nora\System\Reflection;

use ReflectionParameter as Base;

/**
 * ユーティリティファサード
 */
class ReflectionParameter extends Base
{
    use ReflectionTrait;


    public function toString( )
    {
        $str = '';

    
        // 初期値付き
        if ($this->isVariadic())
        {
            $str.="...";
            $str.="$";
            $str.=$this->getName();
            return $str;
        }

        // 参照渡しか
        if ( $this->isPassedByReference() )
        {
            $str.= '&';
        }
        $str.= '$';

        $str.= $this->getName();
        
        if ($this->isOptional())
        {
            $str.='=';

            try
            {
                if ($this->isDefaultValueConstant())
                {
                    $param_val = $this->getDefaultValueConstantName();
                }elseif($this->isDefaultValueAvailable()){
                    $param_val = $this->getDefaultValue();
                }
            } catch (\Exception $e) {

                $param_val = 'EXCEPTIONAL';
            }

            if (empty($param_val))
            {
                $param_val = 'NULL';
            }
            $str.=$param_val;
        }

        return $str;
    }
}

/* vim: set foldmethod=marker st=4 ts=4 sw=4 et: */
