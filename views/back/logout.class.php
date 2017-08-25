<?php
namespace back;

use thedaysoflife\view\ViewBack;
use view\ViewInterface;

class logout extends ViewBack implements ViewInterface {
  protected $requiredPermission = false;

  public function __construct() {
    parent::__construct();
  }

  public function prepare() {
    $this->authentication->userLogout();
  }

}