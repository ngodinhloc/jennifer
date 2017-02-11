<?php
namespace back;
use view\Back;
use core\View;

class home extends Back {
  protected $title = "Dashboard";
  protected $contentTemplate = "home";

  public function __construct() {
    parent::__construct();
  }
}