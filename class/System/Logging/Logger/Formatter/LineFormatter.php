<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\System\Logging\Logger\Formatter;

use Nora\Util\Hash\Hash;
use Nora\Util\Type;
use Nora\System\Logging\Log;
use Nora\System\Logging\LogLevel;
use RuntimeException;

/**
 * フォーマッタ基礎
 */
class LineFormatter extends Formatter
{
    private $_line;
    private $_default_format = "[%(time|Y-m-d G:i:s)] %(level) %(message)";
    private $_format;

    protected function __construct(Hash $options)
    {
        $this->_format = $options->getVal('format', $this->_default_format);
    }

    public function format (Log $log)
    {
        return preg_replace_callback('/%\(([^\)]+)\)/', function($m) use ($log) {
            $method = $m[1];

            $params = explode('|', $m[1]);
            $method = array_shift($params);


            if (!is_callable([$this,'format'.$method]))
            {
                throw new RuntimeException($method."は定義されていません");
            }

            return call_user_func([$this,'format'.$method], $log, $params);
        }, $this->_format);
    }

    private function formatMessage(Log $log, $param)
    {
        $msg = $log->getMessage();

        $keys = array_keys($msg);

        foreach($keys as $k)
        {
            //$str[] = "$k:".Type::stringify($msg[$k]);
            $str[] = "$k:".Type::stringify($msg[$k]);
        }

        return implode(",", $str);
    }

    private function formatTime(Log $log, $params)
    {
        $opt = Hash::create($params, Hash::OPT_ALLOW_UNDEFINED_KEY_GET);

        $format = $opt->getVal(0, 'Y-m-d G:i:s');

        return date($format, $log->getTime());
    }

    private function formatLevel(Log $log, $params)
    {
        switch($log->getLevel())
        {
        case LogLevel::EMERG:
            $cnt = 6;
            break;
        case LogLevel::CRIT:
            $cnt = 6;
            break;
        case LogLevel::ALERT:
            $cnt = 3;
            break;
        case LogLevel::ERR:
            $cnt = 2;
            break;
        case LogLevel::WARNING:
            $cnt = 1;
            break;
        case LogLevel::NOTICE:
        case LogLevel::INFO:
        case LogLevel::DEBUG:
            $cnt = 0;
            break;
        }
        $ast = str_repeat('*', $cnt);
        return sprintf('%3$s%s.%s%3$s',
            $log->getName(),
            LogLevel::toString($log->getLevel()),
            $ast
        );

    }

    private function formatName(Log $log, $params)
    {
        return $log->getName();

    }
}
