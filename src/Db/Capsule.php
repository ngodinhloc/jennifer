<?php

namespace Jennifer\Db;

/**
 * Class Capsule : capsule class of Database table
 * Be able to access to any table by implementing table()
 * @package Jennifer\Db
 */
class Capsule extends Database
{
    /**
     * Set table name
     * @param string $table
     * <pre>prefixes: # => raw</pre>
     * @return $this
     */
    public function table($table)
    {
        $op = substr($table, 0, 1);
        if ($op == "#") {
            $name = substr($table, 1);
        } else {
            $name = "`{$table}`";
        }
        $this->unsetVar();
        $this->tableName = $name;

        return $this;
    }

    /**
     * unset all variables
     */
    private function unsetVar()
    {
        $this->selectCols = null;
        $this->insertCols = null;
        $this->insertVals = null;
        $this->updateVals = null;
        $this->whereCond = null;
        $this->orderBy = null;
        $this->groupBy = null;
        $this->innerJoin = null;
        $this->leftJoin = null;
        $this->rightJoin = null;
        $this->offset = null;
        $this->limit = null;
        $this->result = null;
        $this->foundRows = null;
    }
}