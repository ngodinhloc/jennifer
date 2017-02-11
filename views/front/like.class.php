<?php
namespace front;
use view\Front;
use core\View;

class like extends Front {
  protected $contentTemplate = "index";

  public function __construct() {
    parent::__construct();

    $view       = new View();
    $days       = $view->getBestDays(0, ORDER_BY_LIKE);
    $this->data = ["days" => $days, "order" => ORDER_BY_LIKE];
  }
}