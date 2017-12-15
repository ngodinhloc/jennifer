<?php
namespace back;

use jennifer\fb\FacebookHelper;
use jennifer\view\ViewInterface;
use thedaysoflife\model\Admin;
use thedaysoflife\view\ViewBack;

class days extends ViewBack implements ViewInterface {
  protected $title = "Dashboard :: Days";
  protected $contentTemplate = "days";
  protected $fbHelper;

  public function __construct() {
    parent::__construct();
    $this->admin    = new Admin();
    $this->fbHelper = new FacebookHelper();
  }

  public function prepare() {
    $days       = $this->admin->getDayList(1);
    $this->data = ["days" => $days];
    $this->fbHelper->fbLogin();
  }
}