<?php
namespace jennifer\cache;

/**
 * Class Cache
 * @package cache
 */
class Cache {
  const CACHE_EXPIRE = 36000; // seconds

  /**
   * Create cache key
   * @param string $key
   * @return string
   */
  protected function createKey($key) {
    return md5($key);
  }
}