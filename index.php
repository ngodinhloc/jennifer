<?php
/**
 * Single point entry
 * <pre>mod_rewrite in to redirect all request to this index page (except for the listed directories)
 * process request uri to get view and load view
 * </pre>
 */
require_once("models/autoload.php");
use sys\System;

System::setView();
System::obStart();
include_once("views/include/header.php");
System::loadView();
include_once("views/include/footer.php");
System::obFlush();