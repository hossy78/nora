<?php
namespace Nora\Develop\Debug;

use Nora\System\Context\Context;

/**
 * デバッガ
 */
class Debugger
{
    private $_context;

    public function __construct(Context $context)
    {
        $this->_context = $context;
    }

    public function debug ($var)
    {
        $logger = $this->_context->getService('logger');
        $logger->debug(
            trim(
                $this->dump($var, true)
            )
        );
    }

    public function dump($var, $return = false, $options = [])
    {
        return Dumper::dump($var, $return, $options);
    }
}
