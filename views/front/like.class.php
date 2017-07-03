<?php
namespace front;
use view\Front;
use thedaysoflife\User;

class like extends Front {
  protected $contentTemplate = "like";
  protected $title = "Most Liked Days";

  public function __construct() {
    parent::__construct();

    $user       = new User();
    $days       = $user->getDays(0, ORDER_BY_LIKE);
    $this->data = ["days" => $days, "order" => ORDER_BY_LIKE];
  }
}