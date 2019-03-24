<?php

namespace Jennifer\Cache;

use Jennifer\Cache\Engine\FileCache;
use Jennifer\Cache\Engine\MemCache;

class CacheFactory
{
    /**
     * Load database driver
     * @param string $type
     * @return \Jennifer\Cache\CacheInterface
     */
    public static function createCache($type = null)
    {
        switch ($type) {
            case "meme":
                $cacher = new MemCache();
                break;
            case "file":
            default:
                $cacher = new FileCache();
                break;
        }

        return $cacher;
    }
}