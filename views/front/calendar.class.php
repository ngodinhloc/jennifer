<?php
namespace front;
use view\Front;
use core\View;

class calendar extends Front {
  protected $title = "Calendar Of Life";
  protected $contentTemplate = "calendar";

  public function __construct() {
    parent::__construct();

    $view       = new View();
    $calendar   = $view->getCalendar(0);
    $this->data = ["calendar" => $calendar];
  }
}