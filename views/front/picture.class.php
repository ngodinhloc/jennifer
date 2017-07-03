<?php
namespace front;
use view\Front;
use thedaysoflife\User;

class picture extends Front {
  protected $title = "The Picture Of Life";
  protected $contentTemplate = "picture";

  public function __construct() {
    parent::__construct();

    $user       = new User();
    $picture    = $user->getPicture(0);
    $this->data = ["picture" => $picture];
  }
}