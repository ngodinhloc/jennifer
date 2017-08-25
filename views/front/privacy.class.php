<?php
namespace front;

use thedaysoflife\model\User;
use thedaysoflife\view\ViewFront;
use view\ViewInterface;

class privacy extends ViewFront implements ViewInterface {
  protected $title = "Privacy";
  protected $contentTemplate = "privacy";

  public function __construct() {
    parent::__construct();
    $this->user = new User();
  }

  public function prepare() {
    $info       = $this->user->getInfoByTag("privacy");
    $this->data = ["info" => $info];
  }
}