<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\Network\HTTP\Session;

use Nora\System\Context\Context;
use Nora\Secure\Random;
use Nora\Util\Hash\Hash;
use Nora\Data\KeyValueStore\KeyValueStoreIF;

/**
 * Session
 */
class Session extends Hash
{
    private $_key;
    private $_cookie;
    private $_len;
    private $_session_id;
    private $_lifetime = 60*60;
    private $_storage;

    public function __construct($cookie, KeyValueStoreIF $storage, $key = 'nora-session', $len = 64)
    {
        $this->_cookie = $cookie;
        $this->_storage = $storage;
        $this->_key = $key;
        $this->_len = $len;

        parent::__construct([], Hash::OPT_ALLOW_UNDEFINED_KEY_SET|Hash::OPT_ALLOW_UNDEFINED_KEY_GET);
    }

    public function start( )
    {
        if ($this->restore())
        {
            $this->_cookie->set($this->_key, $this->_session_id);
            return;
        }

        $this->_session_id = $this->genSessionID();
        $this->_cookie->set($this->_key, $this->_session_id);
    }

    public function __destruct( )
    {
        $this->save();
    }

    /**
     * セッションIDを取り直す
     */
    public function regen( )
    {
        $new_id = $this->genSessionID();

        $this->_storage->del($this->_session_id);

        $this->_session_id = $new_id;

        $this->_cookie->set($this->_key, $new_id);
    }

    /**
     * セッションIDを取得
     */
    public function sid()
    {
        return $this->_session_id;
    }


    /**
     * セッションを保存する
     */
    public function save ( )
    {
        $this->_storage->set($this->_session_id, $this->toArray(), [
            'created_at' => time()
        ]);
    }

    /**
     * セッションを復元する
     */
    protected function restore( )
    {
        if (!$this->_cookie->has($this->_key))
        {
            return false;
        }

        $id = $this->_cookie->get($this->_key);
        if (!$this->verify($id))
        {
            return false;
        }

        $this->_session_id = $id;
        $this->initValues($this->_storage->get($id));


        return true;
    }

    /**
     * セッションIDを生成する 
     */
    protected function genSessionID( )
    {
        do {
            // 規定バイト数のセッションIDを作成
            $id = Random::string($this->_len);

        }while($this->verify($id)); // 被ってたら取直す処理

        // 予約する
        $this->_storage->set($id, [], [
            'created_at' => time()
        ]);

        return $id;
    }

    /**
     * セッションIDの有効性を確認
     */
    protected function verify($id)
    {
        if (!$this->_storage->has($id))
        {
            return false;
        }

        $data = $this->_storage->get($id, $meta);

        if ($this->_lifetime < time()-$meta['created_at'])
        {
            return false;
        }

        return true;
    }

    public function __toString( )
    {
        return $this->_session_id;
    }
}
