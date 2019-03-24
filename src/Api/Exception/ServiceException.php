<?php

namespace Jennifer\Api\Exception;

class ServiceException extends \Exception
{
    const ERROR_MSG_NO_SERVICE_PERMISSION = "No service permission";
    const ERROR_MSG_INVALID_SERVICE = "Service not found: ";
}