<?php
namespace front;
use view\Front;
use thedaysoflife\User;

class about extends Front {
  protected $title = "About";
  protected $contentTemplate = "about";

  public function __construct() {
    parent::__construct();
    $user       = new User();
    $info       = $user->getInfoByTag("about");
    $this->data = ["info" => $info];
  }
}