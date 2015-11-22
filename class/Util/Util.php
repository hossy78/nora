<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\Util;

use Nora\Util\Hash\Hash;

/**
 * Utilクラス
 * ====================
 */
class Util
{
    public static function hash($data = [], $options = Hash::OPT_DEFAULT)
    {
        return Hash::create($data, $options);
    }

}
