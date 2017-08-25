<?php
/**
 * Single point entry
 * <pre>mod_rewrite in to redirect all request to this index page (except for the listed directories)
 * process request uri to get view and load view
 * </pre>
 */
require_once("models/autoload.php");
use sys\System;

$viewClass = System::loadView();
if ($viewClass) {
  /** @var \view\ViewInterface */
  $view = new $viewClass() or die("View not found: " . $viewClass);
  $view->prepare();
  $view->render();
}