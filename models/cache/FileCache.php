<?php
namespace cache;

class FileCache {
  /**
   * Create key from sql string
   * @param string $sql
   * @return string
   */
  private function createKey($sql) {
    return md5($sql);
  }

  /**
   * Write to cache file
   * @param string $key
   * @param mixed $data
   * @return bool
   */
  public static function writeCache($sql, $data) {
    $key   = self::createKey($sql);
    $file  = CACHE_DIR . $key;
    $array = ['time' => time(), 'data' => $data];
    $json  = json_encode($array);
    if ($json) {
      if (file_put_contents($file, $json)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Get cache in original data format
   * @param string $sql
   * @return bool|mixed
   */
  public
  static function getCache($sql) {
    $key  = self::createKey($sql);
    $file = CACHE_DIR . $key;
    if (file_exists($file)) {
      $content = file_get_contents($file);
      $arr     = json_decode($content, true);
      $time    = $arr['time'];
      $data    = $arr['data'];
      if (CACHE_EXPIRE >= time() - $time) {
        return $data;
      }
    }

    return false;
  }
}