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

use Nora\System\Configuration\Configuration;
use Nora\System\Context\Context as SystemContext;
use Nora\Nora;

/**
 * Module
 */
class Module
{
    private $_context;

    public function __construct ($name, SystemContext $context, $dir)
    {
        $this->_context = new Context($context, $dir);

        // ConfigPathを設定
        $configPath = $this->_context->getFilePath('config');

        // Configを作成
        $this->_config = Configuration::build('module.'.$name, $configPath, $context->getVal('env'), $useCache = false);

        // AutoLoader
        $ns = $this->_config->read(
                'namespace', $context->getService(
                    'config'
                )->read(
                    'module.namespace'
                ) . '\\'.ucfirst($name)
            );

        $this->_context->setVal('ns', $ns);

        $context->getService('autoloader')->register([
            $ns.'\\Controller' => $this->_context->getFilePath('controller'),
            $this->_context->getFilePath('lib')
        ]);
    }

    public function context( )
    {
        return $this->_context;
    }

}
