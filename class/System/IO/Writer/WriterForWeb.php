<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\System\IO\Writer;

use Nora\System\Context\Context;
/**
 * Writer
 */
class WriterForWeb extends Writer
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }


    public function send ( )
    {
        $text = '<html>'.PHP_EOL;
        $text.= '<head>'.PHP_EOL;
        foreach($this->getheaders() as $v)
        {
            $text.= $v.PHP_EOL;
        }
        $text.= '</head>'.PHP_EOL;
        $text.= '<body>'.PHP_EOL;
        $text.= $this->getBuffer();
        $text.= '</body>'.PHP_EOL;
        $text.= '</html>'.PHP_EOL;
        echo $text;
    }

    public function writeSource ($file)
    {
        $this->writeln(
            highlight_file($file, true)
        );
    }
}
