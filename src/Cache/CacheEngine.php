<?php

namespace Jennifer\Cache;

/**
 * Class Cache
 * @package Jennifer\Cache
 */
abstract class CacheEngine
{
    protected $dir;
    protected $time; // seconds

    /**
     * CacheEngine constructor.
     * @param string|null $dir
     * @param int $time
     */
    public function __construct($dir = null, $time = 36000)
    {
        $this->dir = $dir;
        $this->time = $time;

    }
}