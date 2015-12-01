<?php
namespace Nora\Util\Factory;

use Nora\Exception\Exception;

class FactoryException extends Exception
{
    public function __construct($msg)
    {
        parent::__construct($msg);
    }
}
