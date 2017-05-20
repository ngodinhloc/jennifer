<?php
  namespace api;

  interface APIInterface {
    public function response($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES);
  }
  ?>