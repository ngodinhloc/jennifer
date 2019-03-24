<?php

namespace Jennifer\Html\JObject;

use Jennifer\Html\JObject;
use Jennifer\Sys\Config;

/**
 * Class FlexSlider
 * Only one instance of FlexSlider per view
 * @package Jennifer\Html\JObject
 */
class FlexSlider extends JObject
{
    protected $templates = "jobject/flexslider";
    protected $data = ["fullPhotos" => [], "thumbPhotos" => []];

    public function __construct(array $attr = [], array $data = [])
    {
        $this->metaFiles = [Config::getConfig("SITE_URL") . "/plugins/jquery/flexslider/flexslider.min.css",
            Config::getConfig("SITE_URL") . "/plugins/jquery/flexslider/jquery.flexslider.min.js"];
        parent::__construct($attr, $data);
    }
}