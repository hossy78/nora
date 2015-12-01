<?php
/**
 * アノテーションパーサー
 */
namespace Nora\Util\Annotation;

use Nora\System\Reflection;
use Nora\Nora;

class Parser
{

    static public function parse($class)
    {
        $rc = new Reflection\ReflectionClass($class);
        $file = $rc->getFileName();

        $func = function(&$success) use ($class){
                $rc = new Reflection\ReflectionClass($class);

                $class = $rc->DocComment();
                $methods = [];
                foreach($rc->getMethods() as $m)
                {
                    $methods[$m->getName()] = $m->DocComment();
                }

                $annotation = new Annotation([
                    'class' => $class,
                    'methods' => $methods
                ]);
                $success = true;
                return $annotation;
        };


        return $func($success);

        /*
        return Nora::Cache()->useCache(
            $file,
            $func,
            -1,
            false
        );
         */

    }


}
