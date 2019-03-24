<?php

namespace Jennifer\Html\JObject;

use Jennifer\Html\JObject;
use Jennifer\Sys\Config;

/**
 * Class QRCode : render Jquery QR code
 * @package Jennifer\Html\JObject
 */
class QRCode extends JObject
{
    protected $templates = "jobject/qrcode";
    protected $data = ["size" => 150, "border" => 2, "background" => "#fff", "text" => ""];

    public function __construct(array $attr = [], array $data = [])
    {
        $this->metaFiles = [Config::getConfig("SITE_URL") . "/plugins/jquery/qrcode/jquery.qrcode.min.js",];
        parent::__construct($attr, $data);
    }
}