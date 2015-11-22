<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\FileSystem;

use Nora\Util\String\Format\Formatter;
use Nora\Util\Hash\Hash;

/**
 * ファイルInfo
 */
class FileInfo
{
    /**
     * パスをフォーマットする
     */
    public static function format($str)
    {
        static $data = false;

        if ($data === false)
        {
            $data['user'] = function ($params) {
                static $user = false;
                if ($user === false)
                {
                    $user = posix_getpwuid(posix_getuid())['name'];
                }
                return $user;
            };

            $data['time'] = function ($params) {
                $opt = Hash::create($params, Hash::OPT_ALLOW_UNDEFINED_KEY_GET);

                $format = $opt->getVal(0, 'Y-m-d G:i:s');

                return date($format, time());
            };
        }

        return Formatter::format($str, $data);
    }

}
