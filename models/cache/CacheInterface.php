<?php
namespace cache;
interface CacheInterface {
  public function writeCache($key, $data);

  public function getCache($key);
}