<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 0.0.2
 */

namespace Nora\Util\Hash;

use ArrayAccess,IteratorAggregate,Countable;

/**
 * ハッシュIF
 * ====================
 */
interface HashIF extends ArrayAccess,IteratorAggregate,Countable
{
    public function initValues($data, $opt = Hash::OPT_DEFAULT);
    public function setVal($key, $val = null);
    public function &getVal($key, $default = null);
    public function delVal($key);
    public function hasVal($key, &$found_key = null);
}
