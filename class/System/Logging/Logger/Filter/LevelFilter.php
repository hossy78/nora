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
use Nora\System\Logging\LogLevel;

/**
 * フィルタ基礎
 */
class LevelFilter extends Filter
{
    private $_level;

    protected function __construct (Hash $options)
    {
        $this->_level = LogLevel::toInt($options->getVal('level', 'debug'));
    }

    public function filter (Log $log)
    {
        return $log->getLevel() <= $this->_level ? true: false;
    }

}
