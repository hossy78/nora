<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Web;

use Nora\System\Context\Context as SystemContext;
use Nora\Nora;

/**
 * Web Class
 */
class Web
{
    private $_context;
    private $_router;

    public function __construct (SystemContext $context)
    {
        // Web Contextをビルドする
        $this->_context = new Context($context);

        // Routerをビルドする
        $this->_router = new Routing\Router();
    }

    public function router( )
    {
        return $this->_router;
    }

    public function context( )
    {
        return $this->_context;
    }

    public function route ($path, $spec)
    {
        $this->router()->addRoute(new Routing\Route($path, $spec));

        return $this;
    }

    public function start()
    {
        // LOGを作成する
        Nora::info([
            'msg' => 'Access',
            'uri' => $this->_context->request()
        ]);

        // ルータを取得
        $routers = [$this->router()];
        $request = $this->context()->request();

        while(true)
        {
            // ルーティングを終了させる処理
            if (!$routers[0]->hasNext())
            {
                if (count($routers) > 1)
                {
                    array_shift($routers);
                    continue;
                }
                break;
            }

            // ルーティングをする
            if($route = $routers[0]->route($request))
            {
                // ルーティングインデックスを進める
                $routers[0]->next();

                try
                {
                    // 実行
                    $result = $this->dispatch($route->getSpec());

                    // 戻り値がルータであれば、切り替える
                    if ($result instanceof Routing\RouterIF)
                    {
                        array_unshift($routers,$result);
                        continue;
                    }
                }
                catch(Exception\ControllerNotFoundException $e) 
                {
                    $this->logNotice($e->getMessage());
                    $result = false;
                }



                // 戻り値がfalse以外であればディスパッチ終了
                if ($result !== false) {
                    $dispatched = true;
                    break;
                }
            }

            // 再ルート用にインデックスをすすめる
            $routers[0]->next();
        }
    }

    private function dispatch($spec)
    {
        if (is_string($spec))
        {
            return;
        }

        $this->_context->injectionCall($spec, [$this->context()]);
    }

}
