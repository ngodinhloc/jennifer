<?php

namespace Jennifer\Db\Driver;

interface DriverInterface
{
    public function escape($sql);

    /**
     * @param string $sql
     * @return mixed
     * @throws \Jennifer\Db\Exception\DbException
     */
    public function query($sql = "");

    /**
     * @param $act
     * @return mixed
     * @throws \Jennifer\Db\Exception\DbException
     */
    public function checkDB($act);

    /**
     * @return mixed
     * @throws \Jennifer\Db\Exception\DbException
     */
    public function getFoundRows();

    /**
     * @param $result
     * @return mixed
     */
    public function resultToArray($result);
}