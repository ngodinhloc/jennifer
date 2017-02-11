<?php
namespace back;
use sys\System;
use view\Back;
use core\View;

class logout extends Back {
  protected $title = "Dashboard :: Login";
  protected $contentTemplate = "login";
  protected $requiredPermission = false;

  public function __construct() {
    parent::__construct();

    System::userLogout();
  }
}