<?php
namespace jennifer\io;

interface OutputInterface {
  public function ajax($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES);

  public function csv($data = [], $fileName = "");

  public function html($html = "");
}