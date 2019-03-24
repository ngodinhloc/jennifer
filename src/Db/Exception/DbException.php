<?php

namespace Jennifer\Db\Exception;

use Exception;

class DbException extends Exception
{
    const ERROR_MSG_CONNECTION_FAILED = "Could not connect to database server";
    const ERROR_MSG_MISSING_CONFIG = "Missing database configs, check for DB_HOST, DB_USER, DB_PASSWORD, DB_NAME";
    const ERROR_MSG_QUERY_FAILED = "Error occurs when trying to query database";
}