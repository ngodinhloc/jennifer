<?php
namespace cons;

use com\Com;
use com\SimpleImage;
use sys\System;

class ControllerUpload extends Controller {
  public function uploadPhotos() {
    if (!empty($_FILES)) {
      System::sessionStart();
      $image = new SimpleImage();
      $count = count($_FILES['inputfile']['name']);
      if ($count > NUM_PHOTO_UPLOAD) {
        $count = NUM_PHOTO_UPLOAD;
      }

      for ($i = 0; $i < $count; $i++) {
        $temp_file = $_FILES['inputfile']['tmp_name'][$i];
        $file_type = exif_imagetype($temp_file);
        $allowed   = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

        if (in_array($file_type, $allowed)) {
          // make the dir if not exist
          $dir         = DOC_ROOT . PHOTO_DIR;
          $year        = date('Y');
          $month       = date('m');
          $path        = $year . "/" . $month . "/";
          $target_path = $dir . $path;
          if (!file_exists(str_replace('//', '/', $target_path))) {
            mkdir(str_replace('//', '/', $target_path), 0755, true);
          }
          // resize images and create thumb
          $session_id = System::sessionID();
          $time       = time();
          $rand       = mt_rand();
          $name       = $year . $month . "_" . $time . "_" . $session_id . "_" . $rand;

          $full_name  = Com::getPhotoName($name, PHOTO_FULL_NAME);
          $title_name = Com::getPhotoName($name, PHOTO_TITLE_NAME);
          $thumb_name = Com::getPhotoName($name, PHOTO_THUMB_NAME);

          $image->load($temp_file);
          $image->fit_to_width(PHOTO_FULL_WIDTH);
          $image->save($target_path . $full_name, 75);
          $image->fit_to_width(PHOTO_TITLE_WIDTH);
          $image->save($target_path . $title_name);
          $image->thumbnail(PHOTO_THUMB_WIDTH, PHOTO_THUMB_HEIGHT);
          $image->save($target_path . $thumb_name);

          $thumb_url = Com::getPhotoURL($name, PHOTO_THUMB_NAME);
          print('<li id="' . $name . '">
      							<div class="img-wrapper">
      								<img src="' . $thumb_url . '" class="photo-thumb"/>
      								<span class="glyphicon glyphicon-remove"></span>
      							</div>
      					</li>');
        }
        else {
          // invalid file ext
        }
      }
    }
  }
}