<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora;

/**
 * クラスのオートローダ
 * ====================
 *
 * 使い方
 *
 * ```php
 * AutoLoader::register([
 *  'Nora' => '/class'
 * );
 * ```
 */
class AutoLoader
{
    static $_singleton = false;
    private $_library = [];
    private $_ns = [];

    /**
     * 直接呼ぶ必要なし
     *
     * @param array $array
     */
    private function __construct ( )
    {
        // オートローダを登録する
        spl_autoload_register([$this, 'load']);
    }

    /**
     * シングルトンメソッド
     */
    static private function singleton( )
    {
        if (self::$_singleton === false)
        {
            self::$_singleton = new static();
        }

        return self::$_singleton;
    }

    /**
     * 情報を登録する
     *
     * @param array $directory
     * @param array $name
     */
    static public function register ($directory, $name = null)
    {
        if ($name === null)
        {
            if (is_array($directory)) {
                foreach($directory as $k=>$v) {
                    if (is_numeric($k)) $k = null;
                    self::register($v, $k);
                }
                return;
            }
        }

        $directory = realpath($directory);

        if ($name === null)
        {
            self::singleton()->_library[] = $directory;
        }else{
            self::singleton()->_ns[$name][] = $directory;
        }
    }

    /**
     * 情報を取得
     */
    static public function status ( )
    {
        return [
            'library' => self::singleton()->_library,
            'ns' => self::singleton()->_ns
        ];
    }

    /**
     * Load Class
     *
     * @param string $class
     */
    public function load ($required_class)
    {
        foreach($this->_ns as $k=>$v)
        {
            if (0 === strpos($required_class, $k))
            {
                $class = substr($required_class, strlen($k));
                $path =  ltrim(str_replace('\\', '/', $class).".php", '/');

                foreach($v as $dir)
                {
                    $file = $dir.'/'.$path;
                    if (file_exists($file)) {
                        require_once $file;
                        return true;
                    }
                }
            }
        }

        foreach($this->_library as $v)
        {
            $file = '/'.ltrim(str_replace('\\', '/', $required_class).".php", '/');
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
    }
}
