<?php

namespace jennifer\exception;

use Exception;

class ConfigException extends Exception
{
    const ERROR_CODE_REQUIRED_CONFIG_FILE = 900;
    const ERROR_CODE_MISSING_CONFIG_FILE = 901;
    const ERROR_CODE_MISSING_ROUTE_FILE = 902;
    const ERROR_CODE_MISSING_SERVICE_MAP = 903;
    const ERROR_CODE_MISSING_API = 904;
    const ERROR_CODE_MISSING_ROUTER = 905;
    const ERROR_MSG_REQUIRE_CONFIG_FILE = "Environment config file required.";
    const ERROR_MSG_MISSING_CONFIG_FILE = "Missing environment config file";
    const ERROR_MSG_MISSING_ROUTE_FILE = "Missing route config file";
    const ERROR_MSG_MISSING_SERVICE_MAP = "Missing service map";
    const ERROR_MSG_MISSING_API = "Missing API object";
    const ERROR_MSG_MISSING_ROUTER = "Missing router object";
}