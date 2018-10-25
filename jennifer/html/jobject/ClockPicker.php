<?php

namespace jennifer\html\jobject;
use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class ClockPicker: render Jquery clock picker
 * @package jennifer\html\jobject
 */
class ClockPicker extends JObject {
  public $metaFiles = [Config::SITE_URL . "/plugins/jquery/clockpicker/bootstrap.clockpicker.min.js",
                       Config::SITE_URL . "/plugins/jquery/clockpicker/bootstrap.clockpicker.min.css",];
  protected $templates = "jobject/clockpicker";
  protected $data = ["value" => "", "autoClose" => true];
}