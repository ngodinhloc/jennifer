<?php
  namespace back;

  use view\Back;
  use com\Com;
  use html\jobject\PhotoUploader;
  use thedaysoflife\Admin;

  class day extends Back {
    protected $title = "Dashboard :: Edit";
    protected $contentTemplate = "day";

    public function __construct() {
      parent::__construct();

      $id = $this->hasPara("day");
      if ($id) {
        $admin = new Admin();
        $row = $admin->getDayById($id);
        $photoUploader = new PhotoUploader([], ["text"=>"Current photos","currentPhotos" => Com::getPhotoPreviewArray($row["photos"])]);
        $this->data = ["row" => $row, "photoUploader" => $photoUploader->render()];
        $this->addMetaFile(SITE_URL . "/plugins/ckeditor/ckeditor.js");
      }
    }
  }