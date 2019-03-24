<?php

namespace Jennifer\Sys\Exception;

use Exception;

class ConfigException extends Exception
{
    const ERROR_MSG_REQUIRE_CONFIG_FILE = "Environment config file required.";
    const ERROR_MSG_MISSING_CONFIG_FILE = "Missing environment config file";
    const ERROR_MSG_MISSING_ROUTE_FILE = "Missing route config file";
    const ERROR_MSG_MISSING_SERVICE_MAP = "Missing service map";
    const ERROR_MSG_MISSING_API = "Missing API object";
    const ERROR_MSG_MISSING_ROUTER = "Missing router object";
}