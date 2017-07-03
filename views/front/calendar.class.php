<?php
namespace front;
use view\Front;
use thedaysoflife\User;

class calendar extends Front {
  protected $title = "Calendar Of Life";
  protected $contentTemplate = "calendar";

  public function __construct() {
    parent::__construct();

    $user       = new User();
    $calendar   = $user->getCalendar(0);
    $this->data = ["calendar" => $calendar];
  }
}