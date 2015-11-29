<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */
namespace Nora\Data\KeyValueStore;

use Nora\System\FileSystem\FileInfo;

/**
 * KVS for Mongo
 */
class MongoDB implements KeyValueStoreIF
{
    private $_db;

    public function __construct($table)
    {
        $this->_db = $table;
    }

    public function has($id)
    {
        return !!$this->_db->findOne([
            'key' => $id
        ]);
    }

    public function get($id, &$meta = null)
    {
        if ($this->has($id))
        {
            $data = $this->_db->findOne([
                'key' => $id
            ]);

            $meta = $data['meta'];

            return $data['data'];
        }

        return null;
    }

    public function set($id, $value, $meta = [])
    {
        $data = [
            'meta' => array_merge([
                'time' => time()
            ], $meta),
            'data' => $value
        ];
        $this->_db->update([
            'key' => $id
        ], $data, [
            'upsert' => true
        ]);

        return $data;
    }

    public function del($id)
    {
        $this->_db->delete([
            'key' => $id
        ]);
    }


}
