<?php
namespace back;

use jennifer\html\jobject\PhotoUploader;
use jennifer\view\ViewInterface;
use thedaysoflife\com\Com;
use thedaysoflife\model\Admin;
use thedaysoflife\sys\Configs;
use thedaysoflife\view\ViewBack;

class day extends ViewBack implements ViewInterface {
  protected $title = "Dashboard :: Edit";
  protected $contentTemplate = "day";

  public function __construct() {
    parent::__construct();
    $this->admin = new Admin();
  }

  public function prepare() {
    $id = $this->hasPara("day");
    if ($id) {
      $row           = $this->admin->getDayById($id);
      $photoUploader = new PhotoUploader([], ["text"          => "Current photos",
                                              "currentPhotos" => Com::getPhotoPreviewArray($row["photos"])]);
      $this->data    = ["row"           => $row,
                        "daySelect"     => Com::getDayOptions($row["day"]),
                        "monthSelect"   => Com::getMonthOptions($row["month"]),
                        "yearSelect"    => Com::getYearOptions($row["year"]),
                        "photoUploader" => $photoUploader->render()];
      $this->addMetaFile(Configs::SITE_URL . "/plugins/ckeditor/ckeditor.js");
    }
  }
}