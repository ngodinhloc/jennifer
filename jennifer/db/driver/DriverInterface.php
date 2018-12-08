<?php

namespace jennifer\db\driver;

use jennifer\exception\DBException;

interface DriverInterface
{
    public function escapeString($sql);

    /**
     * @param string $sql
     * @return mixed
     * @throws DBException
     */
    public function query($sql = "");

    /**
     * @param $act
     * @return mixed
     * @throws DBException
     */
    public function checkDB($act);

    /**
     * @return mixed
     * @throws DBException
     */
    public function getFoundRows();

    public function resultToArray($result);
}