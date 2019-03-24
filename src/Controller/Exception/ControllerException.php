<?php

namespace Jennifer\Controller\Exception;


class ControllerException extends \Exception
{
    const ERROR_MSG_INVALID_CONTROLLER = "Controller not found: ";
    const ERROR_MSG_NO_CONTROLLER_PERMISSION = "No controller permission";
    const ERROR_MSG_NO_CONTROLLER_ACTION = "Action not found";
    const ERROR_MSG_FAILED_TO_PERFORM_ACTION = "Failed to perform action";
}