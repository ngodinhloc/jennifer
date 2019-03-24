<?php

namespace Jennifer\Html\JObject;

use Jennifer\Html\JObject;
use Jennifer\Sys\Config;

/**
 * Class DatePicker: render Jquery/Bootstrap date picker
 * @package Jennifer\Html\JObject
 */
class DatePicker extends JObject
{
    protected $templates = "jobject/datepicker";
    protected $data = ["value" => "", "autoClose" => true, "startDate" => "", "endDate" => ""];

    public function __construct(array $attr = [], array $data = [])
    {
        $this->metaFiles = [Config::getConfig("SITE_URL") . "/plugins/jquery/datepicker/bootstrap.datepicker.min.js",
            Config::getConfig("SITE_URL") . "/plugins/jquery/datepicker/bootstrap.datepicker.min.css",];
        parent::__construct($attr, $data);
    }
}