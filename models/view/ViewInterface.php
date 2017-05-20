<?php
namespace view;

interface ViewInterface {
  public function posted();

  public function hasPost($name);

  public function hasPara($name);

  public function addMetaTag($tag);

  public function addMetaFile($file);

  public function redirect($url, $paras = []);

  public function render();
}

?>