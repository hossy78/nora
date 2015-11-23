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
 * Headerハンドラー
 */
class HeaderHandler extends Handler
{
    private $_logs = [];

    protected function _post($log)
    {
        $this->_logs[] = $this->format($log);
    }

    protected function initHandler(Hash $options)
    {
        ob_start(function($buffer) {
            $cnt = 0;
            foreach($this->_logs as $k=>$log)
            {
                header(sprintf("X-NORA-LOG-%05d: %s", $k, $log));
            }
            return $buffer;
        });
    }
}
