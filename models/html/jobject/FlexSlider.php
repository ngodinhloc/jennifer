<?php
namespace html\jobject;

use html\JObject;

/**
 * Class FlexSlider
 * Only one instance of FlexSlider per view
 * @package html\jobject
 */
class FlexSlider extends JObject {
  public $metaFiles = [SITE_URL . "/plugins/jquery/flexslider/flexslider.min.css",
                       SITE_URL . "/plugins/jquery/flexslider/jquery.flexslider.min.js"];
  protected $templates = "jobject/flexslider";
  protected $data = ["fullPhotos" => [], "thumbPhotos" => []];
}