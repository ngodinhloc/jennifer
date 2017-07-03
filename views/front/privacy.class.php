<?php
namespace front;
use view\Front;
use thedaysoflife\User;

class privacy extends Front {
  protected $title = "Privacy";
  protected $contentTemplate = "privacy";

  public function __construct() {
    parent::__construct();

    $user       = new User();
    $info       = $user->getInfoByTag("privacy");
    $this->data = ["info" => $info];
  }
}