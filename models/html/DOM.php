<?php
namespace html;

use html\HTML;

class DOM {
  private $html;

  public function __construct() {
    $this->html = new HTML();
  }

  public function __destruct() {
    unset($this->html);
  }

  public function div($name, $id, $class, $propertyList, $innerHTML) {
    $this->html->setTag("div")->setAttribute($id, $name, $class, $propertyList, $innerHTML);

    return $this->html->create();
  }

  public function span($name, $id, $class, $propertyList, $innerHTML) {
    $this->html->setTag("span")->setAttribute($id, $name, $class, $propertyList, $innerHTML);

    return $this->html->create();
  }

  public function ul($name, $id, $class, $propertyList, $innerHTML) {
    $this->html->setTag("ul")->setAttribute($id, $name, $class, $propertyList, $innerHTML);

    return $this->html->create();
  }

  public function li($name, $id, $class, $propertyList, $innerHTML) {
    $this->html->setTag("li")->setAttribute($id, $name, $id, $class, $propertyList, $innerHTML);

    return $this->html->create();
  }
}