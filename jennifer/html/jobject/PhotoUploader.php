<?php

namespace jennifer\html\jobject;

use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class PhotoUploader: Simple photo uploader that allow drag to order preview photos
 * Only one instance of PhotoUploader per view
 * @package jennifer\html\jobject
 */
class PhotoUploader extends JObject
{
    protected $templates = "jobject/photouploader";

    public function __construct(array $attr = [], array $data = [])
    {
        $this->data = ["action" => "uploadPhotos",
            "controller" => "ControllerUpload",
            "maxSize" => Config::getConfig("PHOTO_MAX_SIZE"),
            "currentPhotos" => [],
            "accept" => "image/jpeg,image/gif,image/png",
            "text" => "Have some photos to upload ?",
            "drag" => "Drag photo to change order."];
        parent::__construct($attr, $data);
    }
}