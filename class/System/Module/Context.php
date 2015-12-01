<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\Module;

use Nora\Util\Hash\Hash;
use Nora\System\Context\BaseContext;

/**
 * コンテクスト
 */
class Context extends BaseContext
{
    public function __construct(BaseContext $context, $dir)
    {
        parent::__construct($context);

        $this->setVal('root', $dir);

    }
}
