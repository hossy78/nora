<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\System\Logging\Logger;

use Nora\Util\Hash\Hash;
use Nora\System\Logging\Logger\Handler\Handler;
use Nora\System\Logging\Log;
use Nora\System\Logging\LogLevel;
use Nora\System\Context\Context;
use Nora\Exception\UndefinedMethodException;

/**
 * ロガー
 */
class Logger 
{
    /**
     * 処理ハンドラ
     */
    private $_handlers = [];

    /**
     * 処理ハンドラ
     */
    private $_context;

    /**
     * ロガー名
     */
    private $_name;

    //static function create($options, Context $context)
    public function __construct($name)
    {
        $this->_name = $name;
    }

    /**
     * ロガー名を取得する
     */
    public function getLoggerName()
    {
        return $this->_name;
    }


    /**
     * ロガーを作成する
     */
    static public function create($name)
    {
        return new static($name);
    }

    /**
     * ロガーをビルドアップする
     */
    static public function build($options)
    {
        $options = Hash::create($options, Hash::OPT_ALLOW_UNDEFINED_KEY_GET);

        $logger = self::create($options->getVal('name', 'Nora'));
        foreach($options->getVal('handlers', []) as $h)
        {
            $logger->addHandler(
                Handler::create($h)
            );
        }

        return $logger;
    }

    /**
     * ハンドラを追加する
     */
    public function addHandler($spec)
    {
        $this->_handlers[] = $spec;
        return true;
    }

    public function __call($name, $params)
    {
        if (defined('Nora\System\Logging\LogLevel::'.strtoupper($name)))
        {
            $level = constant('Nora\System\Logging\LogLevel::'.strtoupper($name));
            array_unshift($params, $level);
            return call_user_func_array([$this, 'log'], $params);
        }

        throw new UndefinedMethodException($this, $name);
    }

    private function log($level, $msg)
    {
        if (!is_array($msg))
        {
            $msg = [
                'msg' => $msg
            ];
        }

        $log = Log::create($level, $this->getLoggerName(), $msg);

        // ハンドラへ通知する
        foreach($this->_handlers as $h)
        {
            $h->post($log);
        }
    }
        


    private function post($level, $msg, $tags, $context)
    {
        $context = array_merge([
            'time' => time(),
            'ua' => $this->_context->client()['ua'],
            'ip' => $this->_context->client()['ip'],
            'user' => $this->_context->server()['owner'],
            'host' => $this->_context->server()['hostname'],
            'level' => LogLevel::toString($level),
            'level_no' => $level
        ], $context);


        $log = Log::create($level, $msg, $tags, $context);

        foreach($this->_handlers as $v)
        {
            $v->post($log);
        }
    }
}
