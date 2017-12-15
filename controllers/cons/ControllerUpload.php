<?php
namespace cons;

use jennifer\controller\Controller;
use jennifer\file\SimpleImage;
use jennifer\sys\Globals;
use thedaysoflife\com\Com;

class ControllerUpload extends Controller {

  public function __construct() {
    parent::__construct();
  }

  /**
   * Upload photos
   * @return string
   */
  public function uploadPhotos() {
    $files = Globals::files("inputfile");
    if ($files) {
      $image = new SimpleImage();
      $count = count($files['name']) > NUM_PHOTO_UPLOAD ? NUM_PHOTO_UPLOAD : count($files['name']);
      for ($i = 0; $i < $count; $i++) {
        $tempFile = $files['tmp_name'][$i];
        if ($tempFile) {
          $fileType = exif_imagetype($tempFile);
          $fileSize = filesize($tempFile);
          $allowed  = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

          if (in_array($fileType, $allowed)) {
            if ($fileSize <= PHOTO_MAX_SIZE * 1000000) {
              list($photoDir, $name) = $this->initPhotoInfo();
              $fullName  = Com::getPhotoName($name, PHOTO_FULL_NAME);
              $titleName = Com::getPhotoName($name, PHOTO_TITLE_NAME);
              $thumbName = Com::getPhotoName($name, PHOTO_THUMB_NAME);

              $image->load($tempFile);
              $image->fit_to_width(PHOTO_FULL_WIDTH);
              $image->save($photoDir . $fullName, 75);
              $image->fit_to_width(PHOTO_TITLE_WIDTH);
              $image->save($photoDir . $titleName);
              $image->thumbnail(PHOTO_THUMB_WIDTH, PHOTO_THUMB_HEIGHT);
              $image->save($photoDir . $thumbName);

              $thumbURL = Com::getPhotoURL($name, PHOTO_THUMB_NAME);
              $this->result .= $this->createPhotoHTML($name, $thumbURL);
            }
            else {
              $this->result = "File size is too big (Maximum size is " . PHOTO_MAX_SIZE . "MB)";
            }
          }
          else {
            $this->result = "Invalid file extension (Only GIF, PNG, JPG, JPEG)";
          }
        }
      }
    }

    return $this->result;
  }

  /**
   * Create photo HTML
   * @param string $name
   * @param string $thumbURL
   * @return string
   */
  private function createPhotoHTML($name, $thumbURL) {
    return '<li id="' . $name . '">
      							<div class="img-wrapper">
      								<img src="' . $thumbURL . '" class="photo-thumb"/>
      								<span class="glyphicon glyphicon-remove"></span>
      							</div>
      					</li>';
  }

  /**
   * Get photo dir, if not existing then create one
   * @return array
   */
  private function initPhotoInfo() {
    $year     = date('Y');
    $month    = date('m');
    $photoDir = DOC_ROOT . PHOTO_DIR . $year . "/" . $month . "/";
    if (!file_exists(str_replace('//', '/', $photoDir))) {
      mkdir(str_replace('//', '/', $photoDir), 0755, true);
    }

    $sessionID = Globals::sessionID();
    $rand      = mt_rand();
    $name      = $year . $month . "_" . time() . "_" . $sessionID . "_" . $rand;

    return [$photoDir, $name];
  }
}