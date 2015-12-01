<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\System\Web;

/**
 * レスポンス制御
 */
class Response
{
    private $_exit_method;
    private $_body;
    private $_headers = [];
    private $_cookies = [];
    private $_status = 200;
    private $_defaultCookieOptions = [
        'path' => null,
        'domain' => null,
        'secure' => true,
        'expires' => 60*60*3600
    ];
    private $_context;


    public function defaultCookieOptions($params)
    {
        $this->_defaultCookieOptions = array_merge(
            $this->_defaultCookieOptions,
            $params
        );
    }


    /**
     * @var array HTTP status codes {{{
     */
    public static $codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required'
    ); // }}}

    public function __construct ($context)
    {
        $this->_context = $context;
        $this->_exit_method = function( ) {
            exit(0);
        };
    }

    static public function createResponse($context)
    {
        return new Response($context);
    }

    /**
     * レスポンスを空にする
     *
     * @return Response
     */
    public function clear( )
    {
        if (ob_get_length() > 0)
        {
            ob_clean();
        }
        $this->_body = "";
        $this->_status = 200;
        $this->_headers = [];
        return $this;
    }

    /**
     * レスポンスを書き込む
     *
     * @param string $body
     * @return Response
     */
    public function write($body)
    {
        $this->_body .= $body;
        return $this;
    }

    /**
     * ステータスをセット
     *
     * @param int $code
     * @return Response
     */
    public function status($code)
    {
        $this->_status = $code;
        return $this;
    }

    /**
     * Adds a header to the response.
     *
     * @param string|array $name Header name or array of names and values
     * @param string $value Header value
     * @return object Self reference
     */
    public function header($name, $value = null) {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->_headers[$k] = $v;
            }
        }
        else {
            $this->_headers[$name] = $value;
        }
        return $this;
    }


    protected function callExit()
    {
        call_user_func($this->_exit_method);
    }

    /**
     * レスポンスを送信する
     */
    public function send( )
    {
        if (ob_get_length() > 0)
        {
            $this->write(ob_get_contents());
            ob_clean();
        }

        if (!headers_sent()) {
            $this->sendHeaders();
        }


        echo $this->_body;


        $this->callExit();
    }

    public function sendHeaders( )
    {
        if(!isset(self::$codes[$this->_status]))
        {
            // var_dump ($this->_status);
        }

        header(
            sprintf(
                '%s %d %s',
                $this->_context->getParent()->_ENV->getVal('SERVER_PROTOCOL', 'HTTP/1.1'),
                $this->_status,
                self::$codes[$this->_status]),
            true,
            $this->_status
        );

        // Send other headers
        foreach ($this->_headers as $field => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    header($field.': '.$v, false);
                }
            }
            else {
                header($field.': '.$value);
            }
        }

        // Send SetCookie
        if (!empty($this->_cookies))
        {
            foreach ($this->_cookies as $k=>$v)
            {
                $header = 'Set-Cookie: ';
                $header.= $v['name'].'='.urlencode($v['value']).';';
                $header.= ' expires='.date('r', time() + $v['expires']).';';
                if ($v['path'])
                {
                    $header.= ' path='.$v['path'].';';
                }
                if ($v['domain'])
                {
                    $header.= ' domain='.$v['domain'].';';
                }
                if ($v['secure'])
                {
                    $header.= ' secure'.';';
                }
                $header = substr($header, 0, strlen($header)-1);
                header($header);
            }
        }

    }

    /**
     * キャッシュヘッダーを付与する
     */
    public function cache($expires = 60*10, $last = null)
    {
        if ($last != null)
        {
            $this->header('Last-Modified', date('r', $last));
        }
        $this->header('Expires', gmdate('D, d M Y H:i:s T', time() + $expires));
        $this->header('Cache-Control', 'private, max-age='.$expires);
        $this->header('Pragma', 'cache');
        return $this;
    }

    /**
     * キャッシュヘッダーを付与する[キャッシュさせない]
     */
    public function nocache( )
    {
        $this->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $this->header('Cache-Control', 'no-cache, must-revalidate');
        return $this;
    }

    /**
     * クッキーを追加
     */
    public function cookie($name, $value, $options = [])
    {
        $this->_cookies[$name] = array_merge($this->_defaultCookieOptions,
            array_merge($options, [
                'name' => $name,
                'value' => $value,
            ]));
        return $this;
    }

    /**
     * ファイルを送信する
     *
     * @param string $file
     */
    public function sendFile($file)
    {
        if (ob_get_length() > 0)
        {
            ob_clean();
        }

        $this->sendHeaders();
        readfile($file);
        $this->callExit();
    }

    /**
     * ファイルポインタを送信する
     *
     * @param stream $fp
     */
    public function sendFilePointer($fp)
    {
        if (ob_get_length() > 0)
        {
            ob_clean();
        }
        $this->sendHeaders();
        $buf = 1024;
        
        while($data = fread($fp, $buf))
        {
            echo $data;
        }
    }
    

    /**
     * Json
     */
    public function json($data)
    {
        $this->header('Content-Type', 'application/json; charset=utf-8');

        $this->write(
            json_encode($data)
        );
    }



}


/* vim: set foldmethod=marker : */
