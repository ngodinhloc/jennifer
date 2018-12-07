<?php

namespace jennifer\db\driver;

use jennifer\cache\FileCache;
use jennifer\cache\MemCache;

class CacherFactory
{
    /**
     * Load database driver
     * @param string $type
     * @return \jennifer\cache\CacheInterface
     */
    public function createCacher($type = null)
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