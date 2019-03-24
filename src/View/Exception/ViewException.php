<?php

namespace Jennifer\View\Exception;

class ViewException extends \Exception
{
    const ERROR_MSG_INVALID_VIEW = "View not found";
    const ERROR_MSG_INVALID_ROUTE = "Invalid route";
}