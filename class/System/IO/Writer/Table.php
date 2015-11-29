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
 * Table
 */
class Table
{
    private $_max = [];
    private $_lines = [];
    private $_writer;

    public function __construct(Writer $writer)
    {
        $this->_writer = $writer;
    }

    public function add( )
    {
        $args = func_get_args();
        foreach($args as $k=>$v)
        {
            $lines = explode("\n", $v);

            foreach($lines as $kk=>$vv)
            {
                if (!isset($this->_max[$k])||$this->_max[$k]<mb_strwidth($vv))
                {
                    $this->_max[$k] = mb_strwidth($vv);
                }
            }
            $args[$k] = $lines;
        }
        $this->_lines[] = $args;
    }

    public function toString($line_sep = "\n", $space=" ", $col_sep=" : ", $col_end = "")
    {
        $rows = [];
        $max_line = [];

        foreach($this->_lines as $k=>$v)
        {
            $max_line[$k] = 0;

            $row = [];
            foreach($v as $kk=>$vv)
            {
                if ($max_line[$k]<count($vv))
                {
                    $max_line[$k] = count($vv);
                }

                foreach ($vv as $kkk=>$vvv)
                {
                    $max = $this->_max[$kk];
                    $len = mb_strwidth($vvv);

                    $row[$kk][$kkk] = $vvv.str_repeat($space, $max - $len);
                }
            }

            $rows[] = $row;
        }


        $lines = [];
        foreach ($rows as $k=>$row)
        {
            $line = '';
            for($i=0;$i<$max_line[$k];$i++)
            {
                for($ii=0;$ii<count($row);$ii++)
                {
                    if (isset($row[$ii][$i]))
                    {
                        $line.= $row[$ii][$i];
                    }
                    else
                    {
                        $line.= str_repeat($space, $this->_max[$ii]);
                    }

                    if ($ii !== count($row)-1)
                    {
                        $line.= $col_sep;
                    }
                    else
                    {
                        $line.= $col_end;
                    }
                }
                $line.= $line_sep;
            }

            foreach($this->_max as $v)
            {
                $line.= str_repeat('-', $v);
            }
            $line.= str_repeat('-', mb_strwidth($col_sep) * (count($this->_max)-1));
            $line.= $line_sep;

            $lines[] = $line;
        }

        return implode("", $lines);
    }
}
