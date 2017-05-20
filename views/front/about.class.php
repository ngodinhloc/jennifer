<?php
namespace front;
use view\Front;
use thedaysoflife\View;

class about extends Front {
  protected $title = "About";
  protected $contentTemplate = "about";

  public function __construct() {
    parent::__construct();
    $view       = new View();
    $info       = $view->getInfoByTag("about");
    $this->data = ["info" => $info];
  }
}