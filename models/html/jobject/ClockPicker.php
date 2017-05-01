<?php
namespace html\jobject;
use html\JObject;

class ClockPicker extends JObject {
  public $metaFiles = [SITE_URL . "/plugins/jquery/clockpicker/bootstrap.clockpicker.min.js",
                       SITE_URL . "/plugins/jquery/clockpicker/bootstrap.clockpicker.min.css",];
  protected $template = "jobject/clockpicker";
  protected $data = ["value" => "", "autoClose" => true];
}