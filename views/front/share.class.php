<?php
  namespace front;
  use view\Front;
  use html\jobject\PhotoUploader;

  class share extends Front {
    protected $title = "Share Your Day";
    protected $contentTemplate = "share";

    public function __construct() {
      parent::__construct();
      $photoUploader = new PhotoUploader([], ["text" => "Have some photos to upload?"]);
      $this->data = ["photoUploader" => $photoUploader->render()];
      $this->addMetaFile(SITE_URL . "/plugins/jquery/jquery.autosize.min.js");
    }
  }