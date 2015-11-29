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
use Nora\Nora;

/**
 * サービススペック
 */
class ServiceSpec
{
    private $_opt;

    static public function create($spec)
    {
        if (is_object($spec) && !($spec instanceof ServiceSpec))
        {
            return new ServiceSpecObject($spec);
        }
        $opt = Hash::create($spec, Hash::OPT_IGNORE_CASE|Hash::OPT_ALLOW_UNDEFINED_KEY_GET);

        return new static($opt);
    }

    public function __construct(Hash $opt)
    {
        $this->_opt = $opt;
    }

    public function isShare()
    {
        return $this->_opt->getVal('share', true);
    }

    public function isAutoStart( )
    {
        return $this->_opt->getVal('autoStart', false);
    }

    public function build($provider)
    {
        if ($this->_opt->hasVal('callback'))
        {
            return call_user_func_array($this->_opt->getVal('callback'), $this->_buildParams($provider));
        }

        $class  = $this->_opt->getVal('class');

        if ($this->_opt->hasVal('method'))
        {
            $method = $this->_opt->getVal('method');
            return call_user_func_array([$class,$method], $this->_buildParams($provider));
        }

        $rc = new \ReflectionClass($class);
        return $rc->newInstanceArgs($this->_buildParams($provider));
    }

    protected function _buildParams($provider)
    {
        if ($this->_opt->hasVal('config'))
        {
            return $this->_injection([$this->_opt->getVal('config')], $provider);
        }

        return $this->_injection($this->_opt->getVal('params', []), $provider);
    }

    protected function _injection($vars, $provider)
    {
        if (is_array($vars)){
            foreach($vars as $k=>$v)
            {
                $vars[$k] = $this->_injection($v, $provider);
            }
            return $vars;
        }

        if (is_string($vars) && substr($vars,0,1) === '@')
        {
            return $provider->get(substr($vars,1));
        }
        return $vars;
    }

}
