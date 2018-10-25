<?php

namespace jennifer\db;
interface DatabaseInterface {
  public function insert();

  public function get($foundRows = false, $cache = false);

  public function update();

  public function delete();

  public function escapeString($sql);

  public function checkDB($act);
}