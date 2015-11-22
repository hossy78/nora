<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\Util\String\Format;

use Nora\Util\Hash\Hash;
use RuntimeException;

/**
 * Formatter
 * ====================
 */
class Formatter
{
    static public function format($format, $data)
    {
        return preg_replace_callback('/%\(([^\)]+)\)/', function($m) use ($data) {
            $method = $m[1];

            $params = explode('|', $m[1]);
            $method = array_shift($params);


            if (!array_key_exists($method, $data))
            {
                throw new RuntimeException($method."は定義されていません");
            }

            if (is_callable($data[$method]))
            {
                return call_user_func($data[$method], $params);
            }
        }, $format);
    }
}
