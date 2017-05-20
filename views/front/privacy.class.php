<?php
namespace front;
use view\Front;
use thedaysoflife\View;

class privacy extends Front {
  protected $title = "Privacy";
  protected $contentTemplate = "privacy";

  public function __construct() {
    parent::__construct();

    $view       = new View();
    $info       = $view->getInfoByTag("privacy");
    $this->data = ["info" => $info];
  }
}