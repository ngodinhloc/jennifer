<?php
namespace front;
use view\Front;
use thedaysoflife\View;

class picture extends Front {
  protected $title = "The Picture Of Life";
  protected $contentTemplate = "picture";

  public function __construct() {
    parent::__construct();

    $view       = new View();
    $picture    = $view->getPicture(0);
    $this->data = ["picture" => $picture];
  }
}