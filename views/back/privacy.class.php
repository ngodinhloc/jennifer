<?php
namespace back;

use thedaysoflife\model\Admin;
use thedaysoflife\view\ViewBack;
use view\ViewInterface;

class privacy extends ViewBack implements ViewInterface {
  protected $title = "Dashboard :: Privacy";
  protected $contentTemplate = "privacy";

  public function __construct() {
    parent::__construct();
    $this->admin = new Admin();
  }

  public function prepare() {
    $tag        = "privacy";
    $info       = $this->admin->getInfoByTag($tag);
    $this->data = ["tag" => $tag, "info" => $info];
    $this->addMetaFile(SITE_URL . "/plugins/ckeditor/ckeditor.js");
  }
}