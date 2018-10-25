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
  public $metaFiles = [Config::SITE_URL . "/plugins/jquery/flexslider/flexslider.min.css",
                       Config::SITE_URL . "/plugins/jquery/flexslider/jquery.flexslider.min.js"];
  protected $templates = "jobject/flexslider";
  protected $data = ["fullPhotos" => [], "thumbPhotos" => []];
}