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

use Nora\System\Logging\Logger\Logger;

/**
 * Client Trait
 */
trait ClientTrait
{
    private $_logger;

    /**
     * ロガーをセットする
     */
    final public function setLogger(Logger $logger)
    {
        $this->_logger = $logger;
    }

    /**
     * ロガーをセットする
     */
    final public function getLogger( )
    {
        if ($this->_logger)
        {
            return $this->_logger;
        }
        return false;
    }

    /**
     * ログをハンドルする
     */
    protected function detectLoggingCall($name, $params)
    {
        if (defined('Nora\System\Logging\LogLevel::'.strtoupper($name)))
        {
            $level = constant('Nora\System\Logging\LogLevel::'.strtoupper($name));

            if ($this->getLogger())
            {
                return call_user_func_array([$this->getLogger(), $name], $params);
            }
        }

        return false;
    }

}
