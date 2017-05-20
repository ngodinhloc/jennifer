<?php
namespace back;
use view\Back;
use sys\System;

class logout extends Back {
  protected $requiredPermission = false;

  public function __construct() {
    parent::__construct();

    System::userLogout();
  }
}