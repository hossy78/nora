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
class Table
{
    private $_collection;

    public function __construct(Connection $con, $name)
    {
        $this->_collection = $con->getTable($name);
    }

    public function __call($name, $params)
    {
        return call_user_func_array([
            $this->_collection,$name
        ], $params);
    }
}
