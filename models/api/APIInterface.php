<?php
namespace api;

interface APIInterface {
  public function process($req);

  public function run();
}