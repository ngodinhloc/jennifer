<?php

namespace Jennifer\Cache;

use Jennifer\Cache\Engine\FileCache;
use Jennifer\Cache\Engine\MemCache;

class CacheEngineFactory
{
    /**
     * Load database driver
     * @param string $engine
     * @param string|null $dir
     * @param int|null $time
     * @return \Jennifer\Cache\CacheEngineInterface
     */
    public static function createCacheEngine(string $engine = null, string $dir = null, int $time = null)
    {
        switch ($engine) {
            case "file":
            default:
                $cache = new FileCache($dir, $time);
                break;
        }

        return $cache;
    }
}