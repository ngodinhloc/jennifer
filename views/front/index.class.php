<?php
namespace front;
use view\Front;
use core\View;

class index extends Front {
  protected $contentTemplate = "index";

  public function __construct() {
    parent::__construct();

    $view       = new View();
    $days       = $view->getBestDays(0, ORDER_BY_ID);
    $this->data = ["days" => $days, "order" => ORDER_BY_ID];
  }
}