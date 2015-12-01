<?php
namespace Nora\Util\Factory;

use ReflectionClass;
use Nora\Nora;

class StandardFactory
{
    protected $classPrefixList = [];

    public function getClassPrefixList( )
    {
        return $this->classPrefixList;
    }

    public function appendClassPrefix($prefix, $sufix = '')
    {
        array_push($this->classPrefixList, [$prefix, $sufix]);
    }

    public function prependClassPrefix($prefix, $sufix = '')
    {
        array_unshift($this->classPrefixList, [$prefix, $sufix]);
    }

    public function create($name, array $arguments = [])
    {
        if (is_object($name)) return $name;

        $log = [];
        foreach($this->getClassPrefixList( ) as list($prefix, $sufix))
        {
            $className = $prefix.ucfirst($name).$sufix;
            array_push($log, $className);

            if (!class_exists($className)) continue;

            $ref = new ReflectionClass($className);

            if (!$this->checkClass($ref))
            {
                $this->reportError(Nora::Message("'%s' は呼び出せないクラスです", [$name]));
            }
            return $ref->newInstanceArgs($arguments);
        }
        $this->reportError(Nora::Message("'%s' は対応するクラスがありません %s", [$name, var_export($log, true)]));
    }

    protected function checkClass(ReflectionClass $msg)
    {
        return true;
    }

    protected function reportError($msg)
    {
        throw new FactoryException($msg);
    }
}
