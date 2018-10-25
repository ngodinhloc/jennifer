<?php

namespace jennifer\html\jobject;

use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class FileUploader: render Jquery file uploader
 * Only one instance of JFileUploader per view
 * @package jennifer\html\jobject
 */
class FileUploader extends JObject {
  public $metaFiles = [Config::SITE_URL . "/plugins/jquery/fileuploader/jquery.fileuploader.min.js",
                       Config::SITE_URL . "/plugins/jquery/fileuploader/jquery.fileuploader.css",];
  protected $templates = "jobject/fileuploader";
  protected $data = ["dragText"       => "Drag & Drop or",
                     "buttonText"     => "Browse Files",
                     "limit"          => 10,
                     "maxSize"        => 10,
                     "fileMaxSize"    => 1,
                     "fileExtensions" => "'jpeg', 'jpg', 'png', 'gif'",];
}