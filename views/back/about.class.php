<?php
namespace back;
use view\Back;
use thedaysoflife\Admin;

class about extends Back {
  protected $title = "Dashboard :: About";
  protected $contentTemplate = "about";

  public function __construct() {
    parent::__construct();

    $admin      = new Admin();
    $tag        = "about";
    $info       = $admin->getInfoByTag($tag);
    $this->data = ["tag" => $tag, "info" => $info];
    $this->addMetaFile(SITE_URL . "/plugins/ckeditor/ckeditor.js");
  }
}