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
 * Echoハンドラー
 */
class StreamHandler extends Handler
{
    private $_stream;

    protected function _post($log)
    {
        $d = fopen($this->_stream, "a");
        flock($d, LOCK_EX);
        fwrite($d, $this->format($log)."\n");
        flock($d, LOCK_UN);
        fclose($d);
    }

    protected function initHandler(Hash $options)
    {
        $this->_stream = FileInfo::format($options->getVal('path', 'php://stdout'));
    }
}
