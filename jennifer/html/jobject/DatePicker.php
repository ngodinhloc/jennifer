<?php

namespace jennifer\html\jobject;
use jennifer\html\JObject;

/**
 * Class DatePicker: render Jquery/Bootstrap date picker
 * @package jennifer\html\jobject
 */
class DatePicker extends JObject {
    protected $templates = "jobject/datepicker";
    protected $data = ["value" => "", "autoClose" => true, "startDate" => "", "endDate" => ""];
    
    public function __construct(array $attr = [], array $data = []) {
        $this->metaFiles = [getenv("SITE_URL") . "/plugins/jquery/datepicker/bootstrap.datepicker.min.js",
                            getenv("SITE_URL") . "/plugins/jquery/datepicker/bootstrap.datepicker.min.css",];
        parent::__construct($attr, $data);
    }
}