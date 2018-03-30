<?php
namespace jennifer\html\jobject;

use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class QRCode : render Jquery QR code
 * @package jennifer\html\jobject
 */
class QRCode extends JObject {
  public $metaFiles = [Config::SITE_URL . "/plugins/jquery/qrcode/jquery.qrcode.min.js",];
  protected $templates = "jobject/qrcode";
  protected $data = ["size" => 150, "border" => 2, "background" => "#fff", "text" => ""];
}