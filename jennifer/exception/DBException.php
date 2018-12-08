<?php

namespace jennifer\exception;

use Exception;

class DBException extends Exception
{
    const ERROR_CODE_CONNECTION_FAILED = 800;
    const ERROR_CODE_MISSING_CONFIGS = 801;
    const ERROR_CODE_QUERY_FAILED = 802;
    const ERROR_MSG_CONNECTION_FAILED = "Could not connect to MySQL server";
    const ERROR_MSG_MISSING_CONFIG = "Missing database configs, check for DB_HOST, DB_USER, DB_PASSWORD, DB_NAME";
}