<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\System\Logging\Logger\Filter;

use Nora\Util\Hash\Hash;
use Nora\System\Logging\Log;

/**
 * フィルタ基礎
 */
abstract class Filter
{
    static function create($options)
    {
        $options = Hash::create($options, Hash::OPT_DEFAULT|Hash::OPT_ALLOW_UNDEFINED_KEY_GET|Hash::OPT_IGNORE_CASE);

        $class = sprintf('Nora\System\Logging\Logger\Filter\%sFilter', ucfirst($options->getVal('type', 'level')));

        return new $class($options);
    }

    abstract function filter (Log $log);

}
