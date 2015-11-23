<?php
namespace Nora\Secure;


/**
 * Secure
 */
class Password
{
    static public $salt = 'NORA';
    static public $saltCount = 10;
    static public $stretch = 1000;


    /**
     * ランダムパスワードを生成する
     *
     * @param int
     * @param array
     * @return string
     */
    static public function generate($length = 8, $chars = ['-', '_', '.','$','#','%'])
    {
        if ($length < 4) throw new \Exception(
            'パスワードが短すぎます'
        );
        $password = Random::string($length, $chars);

        // 強度のテスト　大文字,小文字,数字,記号の混在
        // 先頭,記号,数字不可
        if (
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password) &&
            preg_match('/^[A-Za-z]/', $password)
        )
        {
            return $password;
        }
        return self::generate($length, $chars=[]);
    }

    /**
     * Salt付きパスワードの生成
     */
    static public function hash($string)
    {
        // SALTを複数作成する
        $salts = self::salts($string);

        // ランダムでひとつのSALTを使用する
        $salt = $salts[mt_rand(0, count($salts)-1)];

        // パスワードへSALTを足す
        return self::stretch($salt, $string);
    }


    /**
     * Salt付きパスワードの検証
     */
    public function verify($string, $verify_hash)
    {
        // 発生したであろう全SALT値を検証
        foreach(self::salts($string) as $salt)
        {
            if (self::stretch($salt, $string) === $verify_hash) {
                return true;
            }
        }
        return false;
    }

    /**
     * Sha256
     */
    private function sha256($string)
    {
        return hash('sha256', $string);
    }

    /**
     * Saltを取り出す
     */
    private function salts($string)
    {
        $salts = [];

        // SALTを計算する
        $hash = '';
        for($i=0; $i<self::$saltCount; $i++)
        {
            $salts[] = $hash = self::sha256($hash.self::$salt.$string);
        }

        return $salts;
    }

    private function stretch($salt, $string)
    {
        $hash = '';
        for($i=0; $i<self::$stretch; $i++)
        {
            $hash = self::sha256($hash.$salt.$string);
        }
        return $hash;
    }
}
