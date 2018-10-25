<?php

namespace jennifer\html\jobject;
use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class DatePicker: render Jquery/Bootstrap date picker
 * @package jennifer\html\jobject
 */
class DatePicker extends JObject {
  public $metaFiles = [Config::SITE_URL . "/plugins/jquery/datepicker/bootstrap.datepicker.min.js",
                       Config::SITE_URL . "/plugins/jquery/datepicker/bootstrap.datepicker.min.css",];
  protected $templates = "jobject/datepicker";
  protected $data = ["value" => "", "autoClose" => true, "startDate" => "", "endDate" => ""];
}