<?php
namespace Nora\Secure;


/**
 * Secure
 */
class Random
{
    /**
     * ランダムSha1を生成する
     *
     * @param int
     * @return string Sha1
     */
    static public function sha1($size = 40)
    {
        return sha1(self::bytes($size));
    }

    /**
     * ランダムバイトを生成する
     *
     * @param int
     * @return string Sha1
     */
    static public function bytes ($size = 40)
    {
        $strong = false;
        do {
            $bytes = openssl_random_pseudo_bytes($size, $strong);
        } while ($strong == false);
        return $bytes;
    }

    /**
     * ランダム文字列を生成する
     *
     * @param int
     * @param array
     * @return string
     */
    static public function string($length = 16, $chars = ['-', '_', '.','$','#','%'])
    {
        $size = $max = $bytes = $ret = null;

        $allowed_chars = array_merge([
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
            'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
            'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z',
            ], $chars);

        return self::chars($length, $allowed_chars);
    }


    /**
     * ランダム数列を生成する
     *
     * @param int
     * @param array
     * @return string
     */
    static public function number($length = 6)
    {
        $size = $max = $bytes = $ret = null;

        $allowed_chars = array_merge([
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ]);

        return intval(self::chars($length, $allowed_chars));
    }

    /**
     * ランダム文字列を生成する
     *
     * @param int
     * @param array
     * @return string
     */
    static public function chars($length, $allowed_chars)
    {
        $size = $max = $bytes = $ret = null;

        $max = count($allowed_chars) - 1;

        if (0x7FFFFFFF < $max) {
            return self::chars($length, $chars);
        }

        $size = 4 * $length;
        $bytes = self::bytes($size);
        $ret = '';
        for ($i = 0; $i < $length; $i++) {
            $var = unpack('Nint', substr($bytes, $i, 4))['int'] & 0x7FFFFFFF;
            $fp = (float) $var / 0x7FFFFFFF;
            $ret.= $allowed_chars[(int) round($max * $fp)];
        }
        return $ret;
    }

    /**
     * Sha256
     */
    public static function sha256($string)
    {
        return hash('sha256', $string);
    }

    /**
     * Basic認証
     *
     * @return string user
     */
    public static function basic($cb, $message = 'AUTH')
    {
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
            if (self::securePasswordVerify($_SERVER['PHP_AUTH_PW'], $cb($_SERVER['PHP_AUTH_USER'])));
            {
                return $_SERVER['PHP_AUTH_USER'];
            }
        }

        header('WWW-Authenticate: Basic realm="'.$message.'"');
        header('Content-Type: text/plain; charset=utf-8');
        die('このページを見るには認証が必要です');

        // PASSWORDの生成方法
        //$password = $secure->securePasswordEncrypt('1234');
        //var_dump($password);
    }
}
