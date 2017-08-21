<?php
namespace html\jobject;
use html\JObject;

class ColorPicker extends JObject {
  public $metaFiles = [SITE_URL . "/plugins/jquery/colorpicker/jquery.colorpicker.min.css",
                       SITE_URL . "/plugins/jquery/colorpicker/jquery.colorpicker.min.js"];
  protected $templates = "jobject/colorpicker";
  protected $data = ["value" => "#ffffff"];

}