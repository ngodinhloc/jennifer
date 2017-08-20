<?php
  namespace view;

  interface ViewInterface {
    public function setData($data);

    public function getData();

    public function posted();

    public function hasPost($name);

    public function hasPara($name);

    public function addMetaTag($tag);

    public function addMetaFile($file);

    public function render();
  }