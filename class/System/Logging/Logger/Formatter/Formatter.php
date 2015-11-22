<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\System\Logging\Logger\Formatter;

use Nora\Util\Hash\Hash;
use Nora\System\Logging\Log;

/**
 * フォーマッタ基礎
 */
abstract class Formatter
{
    static function create($options)
    {
        $options = Hash::create($options, Hash::OPT_DEFAULT|Hash::OPT_ALLOW_UNDEFINED_KEY_GET|Hash::OPT_IGNORE_CASE);

        // フォーマット
        $class = sprintf('Nora\System\Logging\Logger\Formatter\%sFormatter', ucfirst($options->getVal('type', 'string')));

        return new $class($options);
    }

    abstract function format (Log $log);

}
