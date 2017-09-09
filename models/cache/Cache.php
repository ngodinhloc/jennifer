<?php
namespace cache;

/**
 * Class Cache
 * @package cache
 */
class Cache {
  const CACHE_EXPIRE = 36000;

  /**
   * Create cache key
   * @param string $key
   * @return string
   */
  protected function createKey($key) {
    return md5($key);
  }
}