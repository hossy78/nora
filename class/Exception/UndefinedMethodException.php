<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\Exception;

/**
 */
class UndefinedMethodException extends Exception
{
    public function __construct( $obj, $method)
    {
        parent::__construct(get_class($obj).'::'.$method);
    }
}
