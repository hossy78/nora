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

use Nora\System\Context\Context as SystemContext;
use Nora\Nora;

/**
 * ModuleBuilder
 */
class ModuleBuilder
{
    private $_context;
    private $_module_dir;
    private $_modules = [];

    public function __construct (SystemContext $context)
    {
        $this->_context = $context;

        $this->_module_dir = $context->getFilePath(
            $context->getService('config')->read('module.dir', 'modules')
        );
    }

    public function getModule($name)
    {
        if (!isset($this->_modules[$name]))
        {
            $this->_modules[$name] = $this->createModule($name);
        }

        return $this->_modules[$name];
    }

    protected function createModule($name)
    {
        $dir = $this->_module_dir.'/'.$name;

        return new Module($name, $this->_context, $dir);
    }
}
