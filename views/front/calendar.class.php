<?php
namespace front;

use thedaysoflife\model\User;
use thedaysoflife\view\ViewFront;
use view\ViewInterface;

class calendar extends ViewFront implements ViewInterface {
  protected $title = "Calendar Of Life";
  protected $contentTemplate = "calendar";

  public function __construct() {
    parent::__construct();
    $this->user = new User();
  }

  public function prepare() {
    $calendar   = $this->user->getCalendar(0);
    $this->data = ["calendar" => $calendar];
  }
}