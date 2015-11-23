<?php
namespace Nora\Secure;


/**
 * View Facade
 *
 */
class Basic
{

    /**
     * Basic認証
     *
     * @return string user
     */
    self public function auth($cb, $message = 'AUTH')
    {
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
            $user = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            $hash = $cb($user);

            if(Password::verify($pass, $hash))
            {
                return $user;
            }
        }

        header('WWW-Authenticate: Basic realm="'.$message.'"');
        header('Content-Type: text/plain; charset=utf-8');
        die('このページを見るには認証が必要です');
    }
}
