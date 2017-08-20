<?php
namespace back;
use view\Back;

class logout extends Back {
  protected $requiredPermission = false;

  public function __construct() {
    parent::__construct();

    $this->authentication->userLogout();
  }
}