<?php
namespace Nora\System\Web;


use Nora\System\Context\Context as SystemContext;
use Nora\System\Context\BaseContext;
use Nora\Util\Hash\Hash;

/**
 * Web Context
 *
 * @readme doc/Context.md
 */
class Context extends BaseContext
{
    private $_is_https = false;
    private $_host_name = false;
    private $_from_ip = false;
    private $_request_url = false;
    private $_request_method = false;
    private $_request_port = false;
    private $_env;

    private $_system_context;

    public function __construct (SystemContext $context)
    {
        parent::__construct($context);

        $this->_system_context = $context;

        $uri = $context->_ENV->getVal('request_uri', '/');
        $query = null;

        if (false !== $pos = strpos($uri,'?'))
        {
            $query = substr($uri, $pos+1);
            $uri = substr($uri, 0, $pos);
        }
        $method = $context->_ENV->getVal('request_method', 'GET');
        $port = $context->_ENV->getVal('server_port', 80);

        $this->data()->initValues(
            [
                'is_https' => 
                    $context->_ENV->getVal('HTTPS', 'off') === 'on' || 
                    $context->_ENV->getVal('SERVER_PORT') === 443
                ,
                'hostname' => 
                    $context->_ENV->getVal('http_host')
                ,
                'from_ip' =>
                    $context->getRemoteIP()
                ,
                'request_uri' => 
                    $uri
                ,
                'request_method' => 
                    $method
                ,
                'port' => 
                    $port
                ,
                'query' => $query,
                '_POST' => $context->_POST,
                '_GET' => $context->_GET,
                '_COOKIE' => $context->_COOKIE,
            ]
        );
    }

    /**
     * 現在のURLを取得する
     *
     * @return URL
     */
    public function requestUri( )
    {
        static $url = null;

        if ($url === null){
            $url = URL::createUrl(
                $this['hostname'],
                $this['request_uri'],
                $this['port'],
                $this['is_https']
            );
        }

        return $url;
    }

    /**
     * リクエストメソッドを取得する
     */
    public function requestMethod( )
    {
        return $this['request_method'];
    }

    /**
     * リクエストを取得する
     *
     * @return Request
     */
    public function request( )
    {
        if (!$this->hasVal('Request'))
        {
            $this->setVal('Request', Request::createRequest($this));
        }
        return $this->getVal('Request');
    }

    /**
     * レスポンスを取得する
     *
     * @return Request
     */
    public function response( )
    {
        static $response = null;

        if ($response === null){
            $response = Response::createResponse( );

            // クッキーの挙動をいじる
            $response->defaultCookieOptions([
                'domain' => $this->url()->host(),
                'path' => '/',
                'expires' => 1000,
                'secure' => false
            ]);
        }

        return $response;
    }

    /**
     * マッチした値を盗る
     */
    public function matched($key, $filter = 'string', $value = null)
    {
        if (!$this->request()->matched()->hasVal($key))
        {
            return $value;
        }

        $val = $this->request()->matched()->getVal($key);
        return F::build($filter)->filter($val);
    }

    /**
     * 出力する
     */
    public function write($data, $status = 200)
    {
        $this->response()->status($status)->write($data);
    }


}
