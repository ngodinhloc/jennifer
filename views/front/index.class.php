<?php
namespace front;
use view\Front;
use thedaysoflife\User;

class index extends Front {
  protected $contentTemplate = "index";

  public function __construct() {
    parent::__construct();

    $user       = new User();
    $days       = $user->getDays(0, ORDER_BY_ID);
    $this->data = ["days" => $days, "order" => ORDER_BY_ID];
  }
}