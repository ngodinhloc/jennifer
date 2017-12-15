<?php
namespace jennifer\html\jobject;
use jennifer\html\JObject;

/**
 * Class DatePicker: render Jquery/Bootstrap date picker
 * @package html\jobject
 */
class DatePicker extends JObject {
  public $metaFiles = [SITE_URL . "/plugins/jquery/datepicker/bootstrap.datepicker.min.js",
                       SITE_URL . "/plugins/jquery/datepicker/bootstrap.datepicker.min.css",];
  protected $templates = "jobject/datepicker";
  protected $data = ["value" => "", "autoClose" => true, "startDate" => "", "endDate" => ""];
}