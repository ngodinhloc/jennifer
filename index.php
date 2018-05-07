<?php
/**
 * Single point entry
 * <pre>mod_rewrite in to redirect all request to this index page (except for the listed directories)
 * process request uri to get view and load view
 * </pre>
 */
require_once("models/autoload.php");

use jennifer\exception\RequestException;
use jennifer\sys\System;

$system = new System();
try {
  $system->loadView()->renderView();
}
catch (RequestException $exception) {
  $exception->getMessage();
}
