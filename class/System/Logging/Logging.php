<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\System\Logging;

use Nora\Util\Hash\Hash;
use Nora\System\Logging\Logger\Logger;
use Nora\System\Context\Context;

/**
 * ロギング
 * ====================
 */
class Logging 
{
    private $_loggers = [];

    public function __construct($settings, $context)
    {
        $this->_loggers = Hash::create([], Hash::OPT_DEFAULT|Hash::OPT_IGNORE_CASE);

        foreach($settings as $k=>$v)
        {
            $this->setup($k, $v, $context);
        }
    }

    public function setup($name, $options, Context $context)
    {
        $this->_loggers[$name] = Logger::create($options, $context);
    }

    public function __call($name, $args)
    {
        return call_user_func_array([$this->_loggers['default'], $name], $args);
    }

}
