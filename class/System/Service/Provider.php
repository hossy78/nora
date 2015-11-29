<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Service;

use Nora\Util\Hash\Hash;
use Nora\System\Engine\Engine;
use Nora\System\Service\Provider as ServiceProvider;
use Nora\Util\Hash\Exception\HashSetOnUndefinedKey;

/**
 * サービスプロバイダー
 */
class Provider
{
    public function __construct( )
    {
        $this->_specs = Hash::create([], Hash::OPT_STRICT|Hash::OPT_IGNORE_CASE|Hash::OPT_ALLOW_UNDEFINED_KEY_SET);
        $this->_cache = Hash::create([], Hash::OPT_STRICT|Hash::OPT_IGNORE_CASE|Hash::OPT_ALLOW_UNDEFINED_KEY_SET);
    }

    /**
     * サービスを取得する
     */
    public function get($name)
    {
        $spec = $this->getSpec($name);
        if ($spec->isShare() && $this->_cache->hasVal($name))
        {
            return $this->_cache->getVal($name);
        }
        $obj = $spec->build($this);
        $this->_cache->setVal($name, $obj);
        return $obj;
    }

    public function has($name)
    {
        return $this->_specs->hasVal($name);
    }

    protected function getSpec($name)
    {
        try
        {
            return $this->_specs[$name];
        }catch(HashSetOnUndefinedKey $e) {
            throw new Exception\UndefinedService($this, $name);
        }
    }

    /**
     * サービスを登録する
     */
    public function set($name, $spec)
    {
        $this->_specs[$name] = ServiceSpec::create($spec);
        if ($this->_specs[$name]->isAutoStart())
        {
            $this->get($name);
        }
    }
}
