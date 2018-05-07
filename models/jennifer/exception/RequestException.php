<?php

namespace jennifer\exception;

use Exception;

class RequestException extends Exception {
  const ERROR_CODE_INVALID_API_REQUEST   = 100;
  const ERROR_CODE_INVALID_API_TOKEN     = 101;
  const ERROR_CODE_INVALID_SERVICE       = 200;
  const ERROR_CODE_INVALID_SERVICE_MODEL = 201;
  const ERROR_CODE_INVALID_CONTROLLER    = 300;
  const ERROR_CODE_INVALID_VIEW          = 400;
  const ERROR_MSG_INVALID_API_REQUEST    = "Invalid API request";
  const ERROR_MSG_INVALID_API_TOKEN      = "Invalid API authenticating token";
  const ERROR_MSG_INVALID_SERVICE        = "Service not found";
  const ERROR_MSG_INVALID_SERVICE_MODEL  = "Service model not found";
  const ERROR_MSG_INVALID_CONTROLLER     = "Controller not found";
  const ERROR_MSG_INVALID_VIEW           = "View not found";
}