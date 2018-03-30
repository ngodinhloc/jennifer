<?php
namespace jennifer\html\jobject;

use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class PhotoUploader: Simple photo uploader that allow drag to order preview photos
 * Only one instance of PhotoUploader per view
 * @package jennifer\html\jobject
 */
class PhotoUploader extends JObject {
  protected $templates = "jobject/photouploader";
  protected $data = ["action"        => "uploadPhotos",
                     "controller"    => "ControllerUpload",
                     "maxSize"       => Config::PHOTO_MAX_SIZE,
                     "currentPhotos" => [],
                     "accept"        => "image/jpeg,image/gif,image/png",
                     "text"          => "Have some photos to upload ?",
                     "drag"          => "Drag photo to change order."];
}