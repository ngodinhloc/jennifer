<?php
  namespace html\jobject;

use html\JObject;

class Signature extends JObject {
  public $metaFiles = ["http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/south-street/jquery-ui.css",
                       SITE_URL . "/plugins/jquery/signature/jquery.signature.css",
                       SITE_URL . "/plugins/jquery/signature/jquery.signature.min.js",
                       SITE_URL . "/plugins/jquery/signature/jquery.ui.touch-punch.min.js"];
  protected $template = "jobject/signature";
  protected $data = ["height" => 150, "jsonValue" => false];
  /*
   check if signature is empty
      isEmpty = ("#signatureID").signature('isEmpty');
   get value of signature in term of JSON
      signatureValue = ("#signatureID").signature('toJSON');
  */
}