<?php

namespace Jennifer\Cache;

interface CacheEngineInterface
{
    /**
     * @param $key
     * @return mixed
     */
    public function createKey($key);

    /**
     * @param $key
     * @param $data
     * @return mixed
     */
    public function writeCache($key, $data);

    /**
     * @param $key
     * @return mixed
     */
    public function getCache($key);
}