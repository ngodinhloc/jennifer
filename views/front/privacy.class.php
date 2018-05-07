<?php

namespace front;

use jennifer\view\ViewInterface;
use thedaysoflife\model\User;
use thedaysoflife\view\ViewFront;

class privacy extends ViewFront implements ViewInterface {
  protected $title = "Privacy";
  protected $contentTemplate = "privacy";
  protected $cache = true;

  public function __construct(User $user = null) {
    parent::__construct();
    $this->user = $user ? $user : new User();
  }

  public function prepare() {
    $info       = $this->user->getInfoByTag("privacy");
    $this->data = ["info" => $info];
  }
}