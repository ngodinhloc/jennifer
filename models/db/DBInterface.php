<?php
  namespace db;
  interface DBInterface {
    public function insert();
    public function get($foundRows = false, $cache = false);
    public function update();
    public function delete();
  }