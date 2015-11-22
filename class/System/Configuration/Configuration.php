<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Configuration;


/**
 * 設定エンジン
 */
class Configuration
{
    private $_config = [];

    /**
     * 設定を書き込む
     */
    public function write ($name, $value = null)
    {
        if (is_array($name))
        {
            foreach($name as $k=>$v) {
                $this->write($k, $v);
            }
            return $this;
        }

        if (is_array($value))
        {
            foreach($value as $k=>$v)
            {
                $key = $name.".".$k;
                $this->write($key, $v);
            }
            return $this;
        }

        $keys = explode('.', $name);
        $root = [];
        $data =& $this->_config;
        foreach($keys as $key)
        {
            $data =& $data[$key];
        }
        if (is_array($data))
        {
            $data = array_merge($data, $value);
        }else{
            $data = $value;
        }
        return $this;
    }

    /**
     * ファイルから設定を読み込む
     */
    public function loadFile ($file, $section = null, $env = null)
    {
        if (is_array($file))
        {
            foreach($file as $v) $this->loadFile($v);
            return $this;
        }
        $data = include $file;

        if ($env != null)
        {
            if (!is_array($env)) $env = [$env];

            $new_data = [];
            foreach($env as $v)
            {
                if (isset($data[$v]) && is_array($data[$v]))
                {
                    $new_data = array_merge_recursive($new_data, $data[$v]);
                }
            }
            $data = $new_data;
        }

        if ($section === null)
        {
            $this->write($data);
        }else{
            $this->write($section, $data);
        }
        return $this;
    }

    /**
     * ディレクトリから設定を読み込む
     */
    public function loadDir ($dir, $section = null, $env = null)
    {
        $list = glob($dir.'/*');

        foreach($list as $file)
        {
            $basename = basename($file);
            $key = substr($basename, 0, strrpos($basename, '.'));
            if ($key === 'config')
            {
                $this->loadFile($file, null, $env);
            }else{
                $this->loadFile($file, $key, $env);
            }
        }

        return $this;
    }

    /**
     * 設定を読みだす
     */
    public function read ($name, $default = null)
    {
        if ($this->has($name))
        {
            return $this->_read($name);
        }
        return $default;
    }

    /**
     * 設定が存在するか
     */
    public function has($name)
    {
        $keys = explode('.', $name);
        $root = [];
        $data = $this->_config;
        foreach($keys as $key)
        {
            if (!array_key_exists($key, $data))
            {
                return false;
            }
            $data =& $data[$key];
        }
        return true;
    }

    /**
     * 取得
     */
    protected function _read($name)
    {
        $keys = explode('.', $name);
        $root = [];
        $data = $this->_config;
        foreach($keys as $key)
        {
            if (!array_key_exists($key, $data))
            {
                return false;
            }
            $data =& $data[$key];
        }
        return $data;
    }

    /**
     * 配列化
     */
    public function toArray( )
    {
        return $this->_config;
    }
}
