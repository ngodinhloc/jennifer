<?php

namespace Jennifer\Html\JObject;

use Jennifer\Html\JObject;
use Jennifer\Sys\Config;

/**
 * Class ClockPicker: render Jquery clock picker
 * @package Jennifer\Html\JObject
 */
class ClockPicker extends JObject
{
    protected $templates = "jobject/clockpicker";
    protected $data = ["value" => "", "autoClose" => true];

    public function __construct(array $attr = [], array $data = [])
    {
        $this->metaFiles = [Config::getConfig("SITE_URL") . "/plugins/jquery/clockpicker/bootstrap.clockpicker.min.js",
            Config::getConfig("SITE_URL") . "/plugins/jquery/clockpicker/bootstrap.clockpicker.min.css",];
        parent::__construct($attr, $data);
    }
}