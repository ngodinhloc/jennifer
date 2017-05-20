<?php
namespace back;
use view\Back;
use thedaysoflife\Admin;

class privacy extends Back {
  protected $title = "Dashboard :: Privacy";
  protected $contentTemplate = "privacy";

  public function __construct() {
    parent::__construct();

    $admin      = new Admin();
    $tag        = "privacy";
    $info       = $admin->getInfoByTag($tag);
    $this->data = ["tag" => $tag, "info" => $info];
    $this->addMetaFile(SITE_URL . "/plugins/ckeditor/ckeditor.js");
  }
}