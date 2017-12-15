<?php
namespace jennifer\html\jobject;
use jennifer\html\JObject;

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