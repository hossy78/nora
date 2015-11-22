<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\System\Error

/**
 * エラーハンドラ
 */
class Handler
{
    public function enable ( )
    {
        // ハンドラ系の登録
        set_error_handler([$this, 'phpErrorHandler']);
        set_exception_handler([$this, 'phpExceptionHandler']);
        register_shutdown_function([$this, 'phpShutdownHandler']);
    }

    static public function create()
    {
        return new ErrorHandler();
    }

    public function phpShutdownHandler()
    {
        $error = error_get_last();

        // Fatal Errorの処理
        if ($error['type'] === E_ERROR) {
            $this->phpErrorHandler(
                $error['type'],
                $error['message'],
                $error['file'],
                $error['line'],
                []
            );
        }
    }

    public function phpExceptionHandler($e)
    {
        // ログを作成
        $log = Log::create(
            $level = LogLevel::ERR,
            $message = [
                'exp' => get_class($e) .'; '.(string) $e,
                'msg' => $e->getMessage()
            ]
        );

        $this->log($log);
    }

    public function phpErrorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        // ログを作成
        $log = Log::create(
            $level = LogLevel::phpToNora($errno),
            $message = sprintf("[%s] %s on %s(%s)", $errno, $errstr, $errfile, $errline)
        );

        $this->log($log);
    }
}
