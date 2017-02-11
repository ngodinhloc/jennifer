<?php
namespace front;
use view\Front;
use core\View;

class share extends Front {
  protected $title = "Share Your Day";
  protected $contentTemplate = "share";

  public function __construct() {
    parent::__construct();

  }
}