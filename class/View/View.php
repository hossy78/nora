<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\View;


use Nora\System\Service\Provider as ServiceProvider;


/**
 * View Facade
 *
 */
class View
{
    private $_service_provider;

    public function __construct (ServiceProvider $provider = null)
    {
        if (is_null($provider))
        {
            $provider = new ServiceProvider( );
            $provider->set([
                'ViewModel' => [
                    'class' => __NAMESPACE__.'\\ViewModel'
                ]
            ]);
        }
        $this->_service_provier = $provider;
    }

}
