<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\System\Logging\Logger\Handler;

use Nora\Util\Hash\Hash;
use Nora\System\Logging\Logger\Formatter\Formatter;
use Nora\System\Logging\Logger\Filter\Filter;
use Nora\System\Logging\Log;

/**
 * ハンドラー基礎
 */
abstract class Handler
{
    private $_formatter;
    private $_filters = [];
    private $_processer = [];

    static function create($options)
    {
        $options = Hash::create($options, Hash::OPT_DEFAULT|Hash::OPT_ALLOW_UNDEFINED_KEY_GET|Hash::OPT_IGNORE_CASE);

        // ハンドラクラス名を作成
        if (false === $class = $options->getVal('class', false))
        {
            $type = $options->getVal('type', 'echo');
            $class = sprintf('Nora\System\Logging\Logger\Handler\%sHandler', ucfirst($type));
        }

        $handler = new $class($options);

        // レベルが設定されていたらレベルフィルターを仕込む
        if($options->hasVal('level'))
        {
            $handler->addFilter(
                Filter::create([
                    'level' => $options->getVal('level')
                ])
            );
        }

        // フォーマッタが指定されていたら
        if ($options->hasVal('formatter'))
        {
            $formatter->setFormatter(
                Formatter::create(
                    $options->getVal('formatter')
                )
            );
        }

        // プロセッサが指定されていたら
        foreach ($options->getVal('processer', []) as $p)
        {
            $handler->addProceccer($p);
        }

        return $handler;
    }

    protected function __construct(Hash $options)
    {
        $this->initHandler($options);
    }

    abstract protected function initHandler(Hash $options);

    public function addFilter($spec)
    {
        $this->_filters[] = $spec;
    }

    public function addProceccer($spec)
    {
        $this->_processer[] = $spec;
    }

    public function setFormatter($spec)
    {
        $this->_formatter = $spec;
    }

    protected function format (Log $log)
    {
        // フォーマッタがなければ
        if ($this->_formatter === null)
        {
            $this->_formatter = Formatter::create([
                'type' => 'line',
            ]);
        }
        return $this->_formatter->format($log);
    }

    public function post($log)
    {
        foreach($this->_filters as $f)
        {
            if (!$f->filter($log))
            {
                return false;
            }
        }

        foreach($this->_processer as $p)
        {
            if (is_string($p ))
            {
                $p::process($log);
            }
            else{
                call_user_func($p, $log);
            }
        }

        $this->_post($log);
    }

}
