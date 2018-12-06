<?php

namespace jennifer\html\jobject;

use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class FlexSlider
 * Only one instance of FlexSlider per view
 * @package jennifer\html\jobject
 */
class FlexSlider extends JObject {
    protected $templates = "jobject/flexslider";
    protected $data = ["fullPhotos" => [], "thumbPhotos" => []];
    
    public function __construct(array $attr = [], array $data = []) {
        $this->metaFiles = [getenv("SITE_URL") . "/plugins/jquery/flexslider/flexslider.min.css",
                            getenv("SITE_URL") . "/plugins/jquery/flexslider/jquery.flexslider.min.js"];
        parent::__construct($attr, $data);
    }
}