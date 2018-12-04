<?php
namespace jennifer\html\jobject;
use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class ColorPicker: render JQuery colour picker
 * @package jennifer\html\jobject
 */
class ColorPicker extends JObject {
  public $metaFiles = [Config::SITE_URL . "/plugins/jquery/colorpicker/jquery.colorpicker.min.css",
                       Config::SITE_URL . "/plugins/jquery/colorpicker/jquery.colorpicker.min.js"];
  protected $templates = "jobject/colorpicker";
  protected $data = ["value" => "#ffffff"];

}