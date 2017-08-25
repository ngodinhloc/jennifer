<?php
namespace front;

use thedaysoflife\model\User;
use thedaysoflife\view\ViewFront;
use view\ViewInterface;

class like extends ViewFront implements ViewInterface {
  protected $contentTemplate = "like";
  protected $title = "Most Liked Days";

  public function __construct() {
    parent::__construct();
    $this->user = new User();
  }

  public function prepare() {
    $days       = $this->user->getDays(0, User::ORDER_BY_LIKE);
    $this->data = ["days" => $days, "order" => User::ORDER_BY_LIKE];
  }
}