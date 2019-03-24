<?php

namespace Jennifer\Cache\Engine;

use Jennifer\Cache\Cache;
use Jennifer\Cache\CacheInterface;

/**
 * Class Memcache: to do implement memcache
 * @package Jennifer\Cache
 */
class MemCache extends Cache implements CacheInterface
{
    public function writeCache($key, $data)
    {
    }

    public function getCache($key)
    {
    }
}