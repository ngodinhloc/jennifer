<?php

namespace Jennifer\Cache;

/**
 * Class Cache
 * @package Jennifer\Cache
 */
abstract class Cache
{
    const CACHE_EXPIRE = 36000; // seconds

    /**
     * Create cache key
     * @param string $key
     * @return string
     */
    protected function createKey($key)
    {
        return md5($key);
    }
}