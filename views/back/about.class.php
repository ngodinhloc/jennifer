<?php
namespace back;

use jennifer\view\ViewInterface;
use thedaysoflife\model\Admin;
use thedaysoflife\sys\Configs;
use thedaysoflife\view\ViewBack;

class about extends ViewBack implements ViewInterface {
  protected $title = "Dashboard :: About";
  protected $contentTemplate = "about";

  public function __construct() {
    parent::__construct();
    $this->admin = new Admin();
  }

  public function prepare() {
    $tag        = "about";
    $info       = $this->admin->getInfoByTag($tag);
    $this->data = ["tag" => $tag, "info" => $info];
    $this->addMetaFile(Configs::SITE_URL . "/plugins/ckeditor/ckeditor.js");
  }
}