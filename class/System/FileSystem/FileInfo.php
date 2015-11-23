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

    /**
     * 書き込む
     */
    public static function putContents($file, $data)
    {
        if (!  is_dir( dirname($file) ) )
        {
            mkdir(dirname($file), 0755, true);
        }
        file_put_contents($file, $data);
    }

    /**
     * 書き込む
     */
    public static function getContents($file)
    {
        return file_get_contents($file);
    }

    /**
     * ディレクトリを作成する
     */
    public static function mkdir ($file, $perm = 0777)
    {
        if (is_dir($file)) return $file;

        if (mkdir($file, $perm, true))
        {
            return $file;
        }

        return false;
    }
}
