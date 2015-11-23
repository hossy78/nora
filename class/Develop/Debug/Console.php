<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\Develop\Debug;

use Nora\Util\Hash\Hash;
//use Nora\Service\Logging\Logger\Formatter\Formatter;
use Nora\System\Logging\Log;
use Nora\System\FileSystem\FileInfo;

/**
 * Debug Console
 */
class Console
{
    private $_logs;

    public function __construct( )
    {
        ob_start(function($buffer) {
            return $buffer . '<script>'.$this->_logs.'</script>';
        });
    }

    public function log($var)
    {
        $this->console('log', $var);
    }

    public function debug($var)
    {
        $this->console('debug', $var);
    }

    public function info($var)
    {
        $this->console('info', $var);
    }

    public function warn($var)
    {
        $this->console('warn', $var);
    }

    public function error($var)
    {
        $this->console('error', $var);
    }

    protected function console($name, $var)
    {
        $this->_logs .= sprintf('console.%s(%s);', $name, json_encode($var));
    }
}
