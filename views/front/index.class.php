<?php
namespace front;

use jennifer\view\ViewInterface;
use thedaysoflife\model\User;
use thedaysoflife\view\ViewFront;

class index extends ViewFront implements ViewInterface {
  protected $contentTemplate = "index";

  public function __construct() {
    parent::__construct();
    $this->user = new User();
  }

  public function prepare() {
    $days       = $this->user->getDays(0, User::ORDER_BY_ID);
    $this->data = ["days" => $days, "order" => User::ORDER_BY_ID];
  }
}