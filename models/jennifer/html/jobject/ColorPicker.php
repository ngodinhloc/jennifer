<?php
namespace jennifer\html\jobject;
use jennifer\html\JObject;

/**
 * Class ColorPicker: render JQuery colour picker
 * @package html\jobject
 */
class ColorPicker extends JObject {
  public $metaFiles = [SITE_URL . "/plugins/jquery/colorpicker/jquery.colorpicker.min.css",
                       SITE_URL . "/plugins/jquery/colorpicker/jquery.colorpicker.min.js"];
  protected $templates = "jobject/colorpicker";
  protected $data = ["value" => "#ffffff"];

}