<?php

namespace Jennifer\Cache\Exception;

use Exception;

class FileCacheException extends Exception
{
    const ERROR_FAILED_TO_PUT_CONTENT = "Failed to put file content";
    const ERROR_FAILED_TO_GET_CONTENT = "Failed to get file content";
}