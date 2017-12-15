<?php
namespace jennifer\html\jobject;

use jennifer\html\JObject;

/**
 * Class PhotoUploader: Simple photo uploader that allow drag to order preview photos
 * Only one instance of PhotoUploader per view
 * @package html\jobject
 */
class PhotoUploader extends JObject {
  protected $templates = "jobject/photouploader";
  protected $data = ["action"        => "uploadPhotos",
                     "controller"    => "ControllerUpload",
                     "maxSize"       => PHOTO_MAX_SIZE,
                     "currentPhotos" => [],
                     "accept"        => "image/jpeg,image/gif,image/png",
                     "text"          => "Have some photos to upload ?",
                     "drag"          => "Drag photo to change order."];
}