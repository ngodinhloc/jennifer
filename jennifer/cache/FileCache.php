<?php

namespace jennifer\cache;

use jennifer\exception\IOException;
use jennifer\sys\Config;

/**
 * Class FileCache: store and retrieve cache in text file
 * @package jennifer\cache
 */
class FileCache extends Cache implements CacheInterface
{
    /**
     * Write to cache file
     * @param string $key
     * @param mixed $data
     * @return bool
     */
    public function writeCache($key, $data)
    {
        $file = Config::getConfig("CACHE_DIR") . $this->createKey($key);
        $array = ['time' => time(), 'data' => $data];
        $json = json_encode($array);
        if ($json) {
            try {
                $result = file_put_contents($file, $json);
                if ($result) {
                    return true;
                }
            } catch (IOException $exception) {
                $exception->getMessage();

                return false;
            }
        }

        return false;
    }

    /**
     * Get cache in original data format
     * @param string $key
     * @return bool|mixed
     */
    public function getCache($key)
    {
        $file = Config::getConfig("CACHE_DIR") . $this->createKey($key);
        if (file_exists($file)) {
            try {
                $content = file_get_contents($file);
                if ($content) {
                    $arr = json_decode($content, true);
                    $time = $arr['time'];
                    $data = $arr['data'];
                    if (self::CACHE_EXPIRE >= time() - $time) {
                        return $data;
                    }
                }
            } catch (IOException $exception) {
                $exception->getMessage();

                return false;
            }
        }

        return false;
    }
}