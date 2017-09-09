<?php
namespace front;

use thedaysoflife\model\User;
use thedaysoflife\view\ViewFront;
use view\ViewInterface;

class about extends ViewFront implements ViewInterface {
  protected $title = "About";
  protected $contentTemplate = "about";
  protected $cache = true;

  public function __construct() {
    parent::__construct();
    $this->user = new User();
  }

  public function prepare() {
    $info       = $this->user->getInfoByTag("about");
    $this->data = ["info" => $info];
  }
}