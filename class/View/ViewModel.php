<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\View;


use Nora\Util\Hash\HashDataTrait;


/**
 * ViewModel
 *
 */
class ViewModel
{
    use HashDataTrait;

    public function __construct ( )
    {
    }

    public function __call($name, $params)
    {
        return call_user_func_array(
            $this[$name],
            $params
        );
    }
}
