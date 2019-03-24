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

    public function __construct(string $dir = null, int $time = 36000)
    {
        $this->dir = $dir;
        $this->time = $time;

    }
}