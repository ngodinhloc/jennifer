<?php

namespace jennifer\html\jobject;

use jennifer\html\JObject;
use jennifer\sys\Config;

/**
 * Class Signature: render Jquery signature
 * @package jennifer\html\jobject
 */
class Signature extends JObject {
  public $metaFiles = ["http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/south-street/jquery-ui.css",
                       Config::SITE_URL . "/plugins/jquery/signature/jquery.signature.css",
                       Config::SITE_URL . "/plugins/jquery/signature/jquery.signature.min.js",
                       Config::SITE_URL . "/plugins/jquery/signature/jquery.ui.touch-punch.min.js"];
  protected $templates = "jobject/signature";
  protected $data = ["height" => 150, "jsonValue" => false];
  /*
   check if signature is empty
      isEmpty = ("#signatureID").signature('isEmpty');
   get value of signature in term of JSON
      signatureValue = ("#signatureID").signature('toJSON');
  */
}