<?php

namespace Jennifer\Html\JObject;

use Jennifer\Html\JObject;
use Jennifer\Sys\Config;

/**
 * Class FileUploader: render Jquery file uploader
 * Only one instance of JFileUploader per view
 * @package Jennifer\Html\JObject
 */
class FileUploader extends JObject
{
    protected $templates = "jobject/fileuploader";
    protected $data = ["dragText" => "Drag & Drop or",
        "buttonText" => "Browse Files",
        "limit" => 10,
        "maxSize" => 10,
        "fileMaxSize" => 1,
        "fileExtensions" => "'jpeg', 'jpg', 'png', 'gif'",];

    public function __construct(array $attr = [], array $data = [])
    {
        $this->metaFiles = [Config::getConfig("SITE_URL") . "/plugins/jquery/fileuploader/jquery.fileuploader.min.js",
            Config::getConfig("SITE_URL") . "/plugins/jquery/fileuploader/jquery.fileuploader.css",];
        parent::__construct($attr, $data);
    }
}