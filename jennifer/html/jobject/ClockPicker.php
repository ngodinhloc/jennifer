<?php

namespace jennifer\html\jobject;

use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class ClockPicker: render Jquery clock picker
 * @package jennifer\html\jobject
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