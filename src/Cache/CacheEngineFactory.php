<?php

namespace Jennifer\Cache;

use Jennifer\Cache\Engine\FileCache;

class CacheEngineFactory
{
    /**
     * @param string $engine
     * @param string|null $dir
     * @param int|null $time
     * @return \Jennifer\Cache\CacheEngineInterface
     */
    public static function createCacheEngine($engine = null, $dir = null, $time = null)
    {
        switch ($engine) {
            case "file":
                $cache = new FileCache($dir, $time);
                break;
            default:
                $cache = null;
                break;
        }

        return $cache;
    }
}