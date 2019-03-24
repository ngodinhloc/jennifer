<?php

namespace Jennifer\Api\Exception;

class ApiException extends \Exception
{
    const ERROR_MSG_INVALID_API_REQUEST = "Invalid API request";
    const ERROR_MSG_INVALID_API_TOKEN = "Invalid API authenticating token";
}