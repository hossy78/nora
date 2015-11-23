<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\System\Logging\Logger\Handler;

use Nora\Util\Hash\Hash;
//use Nora\Service\Logging\Logger\Formatter\Formatter;
use Nora\System\Logging\Log;
use Nora\System\FileSystem\FileInfo;

/**
 * Consoleハンドラー
 */
class ConsoleHandler extends Handler
{
    private $_logs = [];

    protected function _post($log)
    {
        $this->_logs[] = $log->getMessage();
    }

    protected function initHandler(Hash $options)
    {
        ob_start(function($buffer) {
            header(sprintf("X-NORA-LOG: %s",  json_encode($this->_logs)));
            return $buffer.'<script>console.log('.json_encode($this->_logs).');</script>")';
        });
    }
}
