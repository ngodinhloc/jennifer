<?php
namespace db\driver;

use cache\FileCache;
use cache\MemCache;

class CacherFactory {
  /**
   * Load database driver
   * @param string $type
   * @return \cache\CacheInterface
   */
  public function createCacher($type = null) {
    switch($type) {
      case "meme":
        $cacher = new MemCache();
        break;
      case "file":
      default:
        $cacher = new FileCache();
        break;
    }

    return $cacher;
  }
}