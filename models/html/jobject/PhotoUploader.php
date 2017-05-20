<?php
  /**
   * Simple photo uploader that allow drag to order preview photos
   * Only one instance of PhotoUploader per view
   */
  namespace html\jobject;

  use html\JObject;

  class PhotoUploader extends JObject {
    protected $template = "jobject/photouploader";
    protected $data = ["action"        => "uploadPhotos",
                       "controller"    => "ControllerUpload",
                       "maxSize"       => PHOTO_MAX_SIZE,
                       "currentPhotos" => [],
                       "accept"        => "image/jpeg,image/gif,image/png",
                       "text"          => "Have some photos to upload ?",
                       "drag"          => "Drag photo to change order."];
  }