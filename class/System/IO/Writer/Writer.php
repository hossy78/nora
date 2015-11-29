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
abstract class Writer
{
    private $_context;
    private $_buf = '';
    private $_headers = [];
    private $_tables = [];

    public function __construct(Context $context)
    {
        $this->_context = $context;
    }

    static public function create(Context $context )
    {
        if ($context->useHtmlOutput())
        {
            return new WriterForWeb($context);
        }

        return new WriterForCLI($context);
    }

    public function writeln($line)
    {
        $this->_buf .= $line.PHP_EOL;
    }

    public function head($head)
    {
        $this->_headers[] = $head;
    }

    protected function getHeaders()
    {
        return $this->_headers;
    }

    protected function getBuffer( )
    {
        return preg_replace_callback('/__table\[(\d)\]__/', function($m) {
            return $this->_tables[$m[1]]->toString();
        }, $this->_buf);
    }

    public function writeTitle($title)
    {
        $this->writeln($title);
        $this->writeln(str_repeat("=", mb_strwidth($title)));
    }

    public function table ($name = null)
    {
        $this->_tables[] = new Table($this);

        $num = count($this->_tables) - 1;

        if ($this->_context->useHtmlOutput())
        {
            $this->writeln("<pre>__table[$num]__</pre>");
        }else{
            $this->writeln("__table[$num]__");
        }

        return $this->_tables[$num];
    }

    public function writeSource ($file)
    {
        $this->writeln( file_get_contents($file));
    }

    public function markdown($text)
    {
        $this->writeln($text);
    }
}
