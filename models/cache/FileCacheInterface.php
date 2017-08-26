<?php
  namespace cache;
  interface FileCacheInterface {
    public static function writeCache($sql, $data);

    public static function getCache($sql);
  }