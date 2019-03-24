<?php

namespace Jennifer\Core;

use Jennifer\Db\Capsule;

/**
 * Base model class, all business classes should extend this core class
 * @package Jennifer\Core
 */
abstract class Model
{
    /** @var \Jennifer\Db\Capsule */
    protected $db;

    /**
     * Model constructor.
     * @param \Jennifer\Db\Capsule|null $db
     */
    public function __construct(Capsule $db = null)
    {
        $this->db = $db ?: new Capsule();
    }

    public function __destruct()
    {
        unset($this->db);
    }
}
