<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Service\Exception;

use Nora\Util\Hash\Hash;
use Nora\System\Engine\Engine;
use Nora\System\Service\Provider as ServiceProvider;
use Nora\Util\Hash\Exception\HashSetOnUndefinedKey;

/**
 * サービス未定義
 */
class UndefinedService extends \Nora\Exception\Exception
{
    public function __construct($sp, $name)
    {
        parent::__construct('Undefined Service '.$name);
    }

}
