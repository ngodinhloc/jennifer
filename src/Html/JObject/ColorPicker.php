<?php

namespace Jennifer\Html\JObject;

use Jennifer\Html\JObject;
use Jennifer\Sys\Config;

/**
 * Class ColorPicker: render JQuery colour picker
 * @package Jennifer\Html\JObject
 */
class ColorPicker extends JObject
{
    protected $templates = "jobject/colorpicker";
    protected $data = ["value" => "#ffffff"];

    public function __construct(array $attr = [], array $data = [])
    {
        $this->metaFiles = [Config::getConfig("SITE_URL") . "/plugins/jquery/colorpicker/jquery.colorpicker.min.css",
            Config::getConfig("SITE_URL") . "/plugins/jquery/colorpicker/jquery.colorpicker.min.js"];
        parent::__construct($attr, $data);
    }
}