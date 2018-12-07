<?php

namespace jennifer\cache;
interface CacheInterface
{
    public function writeCache($key, $data);

    public function getCache($key);
}