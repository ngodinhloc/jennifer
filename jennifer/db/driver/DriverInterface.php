<?php

namespace jennifer\db\driver;
interface DriverInterface {
    public function escapeString($sql);
    
    public function query($sql = "");
    
    public function checkDB($act);
    
    public function getFoundRows();
    
    public function resultToArray($result);
}