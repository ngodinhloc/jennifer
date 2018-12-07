<?php

namespace jennifer\html\jobject;

use jennifer\html\JObject;

/**
 * Class ColorPicker: render JQuery colour picker
 * @package jennifer\html\jobject
 */
class ColorPicker extends JObject
{
    protected $templates = "jobject/colorpicker";
    protected $data = ["value" => "#ffffff"];

    public function __construct(array $attr = [], array $data = [])
    {
        $this->metaFiles = [getenv("SITE_URL") . "/plugins/jquery/colorpicker/jquery.colorpicker.min.css",
            getenv("SITE_URL") . "/plugins/jquery/colorpicker/jquery.colorpicker.min.js"];
        parent::__construct($attr, $data);
    }
}