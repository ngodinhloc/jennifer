<?php
namespace html;
interface HTMLInterface {
  public function setAttribute($id = null, $name = null, $class = null, $propList = null, $innerHTML = null);

  public function setTag($tag);

  public function setID($id);

  public function setName($name);

  public function setClass($class);

  public function setProp($prop);

  public function setInnerHTML($innerHTML);

  public function open();

  public function close();

  public function create();
}