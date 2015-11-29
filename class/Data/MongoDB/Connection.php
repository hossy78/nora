<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\Data\MongoDB;

use Nora\System\FileSystem\FileInfo;

/**
 * KVS for Mongo
 */
class Connection 
{
    private $_db;

    public function __construct($path)
    {
        $c = new \MongoClient(dirname($path));
        $this->_db = $c->{basename($path)};
    }

    public function getTable($name)
    {
        return $this->_db->{$name};
    }
}
