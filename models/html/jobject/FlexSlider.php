<?php
  /**
   * Only one instance of FlexSlider per view
   */
  namespace html\jobject;

  use html\JObject;

  class FlexSlider extends JObject {
    public $metaFiles = [SITE_URL . "/plugins/jquery/flexslider/flexslider.min.css",
                         SITE_URL . "/plugins/jquery/flexslider/jquery.flexslider.min.js"];
    protected $templates = "jobject/flexslider";
    protected $data = ["fullPhotos" => [], "thumbPhotos" => []];
  }