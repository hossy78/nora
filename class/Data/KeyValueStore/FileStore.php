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
 * KVS for File
 */
class FileStore implements KeyValueStoreIF
{
    private $_path;
    private $_chunk_size = 5;

    public function __construct($dir = '/tmp/store')
    {
        $this->_path = FileInfo::mkdir($dir);
    }

    public function has($id)
    {
        if (file_exists($this->getFilePath($id)))
        {
            return true;
        }
        return false;
    }

    public function get($id, &$meta = null)
    {
        if ($this->has($id))
        {
            $data = unserialize(FileInfo::getContents(
                $this->getFilePath($id)
            ));

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

        FileInfo::putContents($path = $this->getFilePath($id), serialize($data));
        return $path;
    }

    public function del($id)
    {
        unlink($this->getFilePath($id));
    }

    private function getFilePath($id)
    {
        $str = md5($id);
        return $this->_path.'/'.implode('/', str_split($str, $this->_chunk_size));
    }

}
