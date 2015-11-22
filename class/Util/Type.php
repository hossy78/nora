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
 * 型に関する処理
 * ====================
 */
class Type
{
    public static function stringify ($var)
    {
        if (self::isObject($var))
        {
            if (method_exists($var, '__toString'))
            {
                return (String) $var;
            }
            return sprintf(
                'Object(%s#%s)',
                get_class($var),
                spl_object_hash($var)
            );
        }

        if (self::isArray($var))
        {
            return sprintf('Array(%s)', count($var));
        }

        return (String) $var;
    }

    public static function isString($var)
    {
        return is_string($var);
    }

    public static function isObject($var)
    {
        return is_object($var);
    }

    public static function isArray($var)
    {
        return is_array($var);
    }

    public static function isNull($var)
    {
        return is_null($var);
    }

    public static function isResource($var)
    {
        return is_resource($var);
    }

    public static function isScalar($var)
    {
        return
            !self::isObject($var) &&
            !self::isArray($var) &&
            !self::isResouce($var);
    }
}
