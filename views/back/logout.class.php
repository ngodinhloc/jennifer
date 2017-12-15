<?php
namespace back;

use jennifer\view\ViewInterface;
use thedaysoflife\view\ViewBack;

class logout extends ViewBack implements ViewInterface {
  protected $requiredPermission = false;

  public function __construct() {
    parent::__construct();
  }

  public function prepare() {
    $this->authentication->userLogout();
  }
}