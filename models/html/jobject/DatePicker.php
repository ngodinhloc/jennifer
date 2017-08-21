<?php
namespace html\jobject;
use html\JObject;

class DatePicker extends JObject {
  public $metaFiles = [SITE_URL . "/plugins/jquery/datepicker/bootstrap.datepicker.min.js",
                       SITE_URL . "/plugins/jquery/datepicker/bootstrap.datepicker.min.css",];
  protected $templates = "jobject/datepicker";
  protected $data = ["value" => "", "autoClose" => true, "startDate" => "", "endDate" => ""];
}