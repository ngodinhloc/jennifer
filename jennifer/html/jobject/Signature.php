<?php

namespace jennifer\html\jobject;

use jennifer\html\JObject;

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
            getenv("SITE_URL") . "/plugins/jquery/signature/jquery.signature.css",
            getenv("SITE_URL") . "/plugins/jquery/signature/jquery.signature.min.js",
            getenv("SITE_URL") . "/plugins/jquery/signature/jquery.ui.touch-punch.min.js"];
        parent::__construct($attr, $data);
    }
    /*
     check if signature is empty
        isEmpty = ("#signatureID").signature('isEmpty');
     get value of signature in term of JSON
        signatureValue = ("#signatureID").signature('toJSON');
    */
}