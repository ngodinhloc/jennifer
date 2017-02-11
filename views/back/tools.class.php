<?php
namespace back;
use view\Back;
use core\Admin;

class tools extends Back {
  protected $title = "Dashboard :: Tools";
  protected $contentTemplate = "tools";

  public function __construct() {
    parent::__construct();
  }
}