<?php
namespace controller;

interface ControllerInterface {
  public function response($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES);
}