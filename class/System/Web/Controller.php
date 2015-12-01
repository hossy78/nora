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
use Nora\System\Module\Module;
use Nora\System\Web\Context as WebContext;
use Nora\System\Web\Routing\Router as WebRouter;
use Nora\Nora;

use Nora\System\Routing\RouterIF;
use Nora\System\Routing;

use Nora\Util\Annotation;
use Nora\System\Service\Provider as ServiceProvider;

/**
 * Web Class
 */
class Controller implements RouterIF
{
    private $_module, $_webContext;
    private $_router = false;
    private $_mask;
    private $_service_provider;

    static public function create(Module $module, WebContext $webContext, $ctrl, $mask)
    {
        $class = get_called_class();
        $ctrl =  new $class($module, $webContext);

        $ctrl->_mask = $mask;

        return $ctrl;
    }

    public function __construct(Module $module, WebContext $webContext)
    {
        $this->_module = $module;
        $this->_webContext = $webContext;
        $this->_service_provier = new ServiceProvider([
            'view' => [
                'class' => 'Nora\View\View'
            ]
        ]);

        $this->initController( );
    }

    protected function initController( )
    {
    }

    public function router()
    {
        if ($this->_router === false)
        {
            $this->_router = new WebRouter();

            $this->_router->mask($this->_mask);

            // アノテーションを読み込む
            $anot = Annotation\Parser::parse($this);

            // アノテーションからルートを設定
            foreach($anot->getMethods() as $name=>$doc)
            {
                if($doc->hasAttr('route'))
                {
                    foreach($doc->getAttr('route') as $pattern)
                    {
                        $this->_router->map($pattern, function($context) use ($doc, $name) {

                            $injections = $doc->getAttr('inject');
                            array_push($injections, [$this,$name]);

                            return 
                                $this->_webContext->injectionCall(
                                    $injections,
                                    [
                                        $context
                                    ],
                                    [
                                        $this->_service_provier,
                                        $this->_module->context()
                                    ]
                                );
                        });
                    }
                }
            }
        }

        return $this->_router;
    }

    /**
     * ルーティングを行う
     *
     * @param Routing\RequestIF $req
     * @return Routing\Route
     */
    public function route(Routing\RequestIF $req)
    {
        return $this->router()->route($req);
    }

    /**
     * ルーティング要素が存在すればtrue
     *
     * @return bool
     */
    public function hasNext( )
    {
        return $this->router()->hasNext();
    }

    /**
     * ルーティングインデックスを進める
     *
     * @return void
     */
    public function next( )
    {
        $this->router()->next();
    }
}
