<?php

namespace Jennifer\Cache\Engine;

use Jennifer\Cache\CacheEngine;
use Jennifer\Cache\CacheEngineInterface;
use Jennifer\Cache\Exception\FileCacheException;

/**
 * Class FileCache: store and retrieve cache in text file
 * @package Jennifer\Cache
 */
class FileCache extends CacheEngine implements CacheEngineInterface
{
    /**
     * @param string $key
     * @return mixed|string
     */
    public function createKey($key)
    {
        return md5($key);
    }

    /**
     * Write to cache file
     * @param string $key
     * @param mixed $data
     * @return bool
     * @throws \Jennifer\Cache\Exception\FileCacheException
     */
    public function writeCache($key, $data)
    {
        $file = $this->dir . $this->createKey($key);
        $array = ['time' => time(), 'data' => $data];
        $json = json_encode($array);
        if ($json) {
            try {
                $result = file_put_contents($file, $json);
                if ($result) {
                    return true;
                }
            } catch (\Exception $exception) {
                throw new FileCacheException(FileCacheException::ERROR_FAILED_TO_PUT_CONTENT . $exception->getMessage());
            }
        }

        return false;
    }

    /**
     * Get cache in original data format
     * @param string $key
     * @return bool|mixed
     * @throws \Jennifer\Cache\Exception\FileCacheException
     */
    public function getCache($key)
    {
        $file = $this->dir . $this->createKey($key);
        if (file_exists($file)) {
            try {
                $content = file_get_contents($file);
                if ($content) {
                    $arr = json_decode($content, true);
                    $time = $arr['time'];
                    $data = $arr['data'];
                    if ($this->time >= time() - $time) {
                        return $data;
                    }
                }
            } catch (\Exception $exception) {
                throw new FileCacheException(FileCacheException::ERROR_FAILED_TO_GET_CONTENT . $exception->getMessage());
            }
        }

        return false;
    }
}