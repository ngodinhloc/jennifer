<?php
namespace jennifer\cache;

use jennifer\exception\IOException;

/**
 * Class FileCache: store and retrieve cache in text file
 * @package cache
 */
class FileCache extends Cache implements CacheInterface {
  /**
   * Write to cache file
   * @param string $key
   * @param mixed $data
   * @return bool
   */
  public function writeCache($key, $data) {
    $file  = CACHE_DIR . $this->createKey($key);
    $array = ['time' => time(), 'data' => $data];
    $json  = json_encode($array);
    if ($json) {
      try {
        $result = file_put_contents($file, $json);
        if ($result) {
          return true;
        }
      }
      catch (IOException $e) {
      }
    }

    return false;
  }

  /**
   * Get cache in original data format
   * @param string $key
   * @return bool|mixed
   */
  public function getCache($key) {
    $file = CACHE_DIR . $this->createKey($key);
    if (file_exists($file)) {
      try {
        $content = file_get_contents($file);
        if ($content) {
          $arr  = json_decode($content, true);
          $time = $arr['time'];
          $data = $arr['data'];
          if (self::CACHE_EXPIRE >= time() - $time) {
            return $data;
          }
        }
      }
      catch (IOException $e) {
      }
    }

    return false;
  }
}