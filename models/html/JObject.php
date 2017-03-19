<?php
/**
 * Jquery Object class
 */
namespace html;
use tpl\Template;

class JObject extends Template {
  protected $id;
  protected $name;
  protected $class;
  protected $properties = [];
  protected $prop;
  protected $html;

  public function __construct($attr, $data) {
    parent::__construct($this->template, $data);
    $this->initAttributes($attr);
  }

  /**
   * Init object template data
   * @param $attr
   */
  protected function initAttributes($attr) {
    $this->id         = $attr['id'];
    $this->name       = $attr["name"];
    $this->class      = $attr["class"];
    $this->properties = $attr["properties"];
    $this->html       = $attr["html"];
    if (is_array($this->properties)) {
      foreach ($this->properties as $att => $val) {
        $this->prop .= " {$att} = '{$val}'";
      }
    }
  }
}