<?php
namespace front;

use html\jobject\PhotoUploader;
use thedaysoflife\view\ViewFront;
use view\ViewInterface;

class share extends ViewFront implements ViewInterface {
  protected $title = "Share Your Day";
  protected $contentTemplate = "share";

  public function __construct() {
    parent::__construct();
  }

  public function prepare() {
    $photoUploader = new PhotoUploader([], ["text" => "Have some photos to upload?"]);
    $this->data    = ["photoUploader" => $photoUploader->render()];
    $this->addMetaFile(SITE_URL . "/plugins/jquery/jquery.autosize.min.js");
  }
}