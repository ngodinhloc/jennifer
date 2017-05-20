<?php
  namespace html\jobject;

  use html\JObject;

  class QRCode extends JObject {
    public $metaFiles = [SITE_URL . "/plugins/jquery/qrcode/jquery.qrcode.min.js",];
    protected $template = "jobject/qrcode";
    protected $data = ["size" => 150, "border" => 2, "background" => "#fff", "text" => ""];
  }