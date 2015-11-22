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
 * サービススペック
 */
class ServiceSpecObject
{
    public function __construct($obj)
    {
        $this->_obj = $obj;
    }

    public function isShare()
    {
        return true;
    }

    public function build( )
    {
        return $this->_obj;
    }

}
