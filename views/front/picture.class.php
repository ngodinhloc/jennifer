<?php
namespace front;

use jennifer\view\ViewInterface;
use thedaysoflife\model\User;
use thedaysoflife\view\ViewFront;

class picture extends ViewFront implements ViewInterface {
  protected $title = "The Picture Of Life";
  protected $contentTemplate = "picture";

  public function __construct() {
    parent::__construct();
    $this->user = new User();
  }

  public function prepare() {
    $picture    = $this->user->getPicture(0);
    $this->data = ["picture" => $picture];
  }
}