<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\System\Logging\Logger\Processer;

use Nora\Util\Hash\Hash;
use Nora\System\Logging\Logger\Formatter\Formatter;
use Nora\System\Logging\Logger\Filter\Filter;
use Nora\System\Logging\Log;
use Nora\System\Context\Context;

/**
 * ハンドラー基礎
 */
class AddInfoProcesser
{
    public function process(Log $log)
    {
        $log['ip'] = Context::singleton()->getRemoteIP();
        $log['ua'] = Context::singleton()->getUserAgent();
        $log['posix_user'] = Context::singleton()->getPosixUser();
    }
}
