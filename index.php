<?php
/**
 * Single point entry
 * <pre>mod_rewrite in to redirect all request to this index page (except for the listed directories)
 * process request uri to get view and load view
 * </pre>
 */
require_once("models/autoload.php");
use sys\System;
use view\ViewFactory;

$viewClass = System::loadView();
if ($viewClass) {
  $factory = new ViewFactory();
  $view    = $factory->createView($viewClass);
  $view->prepare();
  $view->render();
}