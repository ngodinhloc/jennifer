<?php

namespace jennifer\html\jobject;

use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class Signature: render Jquery signature
 * @package jennifer\html\jobject
 */
class Signature extends JObject
{
    protected $templates = "jobject/signature";
    protected $data = ["height" => 150, "jsonValue" => false];

    public function __construct(array $attr = [], array $data = [])
    {
        $this->metaFiles = ["http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/south-street/jquery-ui.css",
            Config::getConfig("SITE_URL") . "/plugins/jquery/signature/jquery.signature.css",
            Config::getConfig("SITE_URL") . "/plugins/jquery/signature/jquery.signature.min.js",
            Config::getConfig("SITE_URL") . "/plugins/jquery/signature/jquery.ui.touch-punch.min.js"];
        parent::__construct($attr, $data);
    }
    /*
     check if signature is empty
        isEmpty = ("#signatureID").signature('isEmpty');
     get value of signature in term of JSON
        signatureValue = ("#signatureID").signature('toJSON');
    */
}