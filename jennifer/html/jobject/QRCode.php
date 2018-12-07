<?php

namespace jennifer\html\jobject;

use jennifer\html\JObject;

/**
 * Class QRCode : render Jquery QR code
 * @package jennifer\html\jobject
 */
class QRCode extends JObject
{
    protected $templates = "jobject/qrcode";
    protected $data = ["size" => 150, "border" => 2, "background" => "#fff", "text" => ""];

    public function __construct(array $attr = [], array $data = [])
    {
        $this->metaFiles = [getenv("SITE_URL") . "/plugins/jquery/qrcode/jquery.qrcode.min.js",];
        parent::__construct($attr, $data);
    }
}