<?php

namespace Jennifer\Html\JObject;

use Jennifer\Html\JObject;
use Jennifer\Sys\Config;

/**
 * Class PhotoUploader: Simple photo uploader that allow drag to order preview photos
 * Only one instance of PhotoUploader per view
 * @package Jennifer\Html\JObject
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