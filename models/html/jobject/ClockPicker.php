<?php
namespace html\jobject;
use html\JObject;

/**
 * Class ClockPicker: render Jquery clock picker
 * @package html\jobject
 */
class ClockPicker extends JObject {
  public $metaFiles = [SITE_URL . "/plugins/jquery/clockpicker/bootstrap.clockpicker.min.js",
                       SITE_URL . "/plugins/jquery/clockpicker/bootstrap.clockpicker.min.css",];
  protected $templates = "jobject/clockpicker";
  protected $data = ["value" => "", "autoClose" => true];
}