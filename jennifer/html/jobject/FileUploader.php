<?php

namespace jennifer\html\jobject;

use jennifer\html\JObject;

/**
 * Class FileUploader: render Jquery file uploader
 * Only one instance of JFileUploader per view
 * @package jennifer\html\jobject
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
        $this->metaFiles = [getenv("SITE_URL") . "/plugins/jquery/fileuploader/jquery.fileuploader.min.js",
            getenv("SITE_URL") . "/plugins/jquery/fileuploader/jquery.fileuploader.css",];
        parent::__construct($attr, $data);
    }
}