<?php
namespace jennifer\html\jobject;

use jennifer\html\JObject;

/**
 * Class QRCode : render Jquery QR code
 * @package html\jobject
 */
class QRCode extends JObject {
  public $metaFiles = [SITE_URL . "/plugins/jquery/qrcode/jquery.qrcode.min.js",];
  protected $templates = "jobject/qrcode";
  protected $data = ["size" => 150, "border" => 2, "background" => "#fff", "text" => ""];
}