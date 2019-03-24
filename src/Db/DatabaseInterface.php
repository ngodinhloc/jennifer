<?php

namespace Jennifer\Db;
interface DatabaseInterface
{
    public function insert();

    /**
     * @param bool $foundRows
     * @param bool $cache
     * @return mixed
     */
    public function get($foundRows = false, $cache = false);

    public function update();

    public function delete();

    /**
     * @param $sql
     * @return mixed
     */
    public function escape($sql);

    /**
     * @param $act
     * @return mixed
     */
    public function checkDB($act);
}