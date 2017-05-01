<?php
/**
 * Jquery and Bootstrap Object class
 */
namespace html;
use tpl\Template;

class JObject extends Template {
  protected $id;
  protected $class;
  protected $properties = [];
  protected $prop;
  protected $html;
  public $metaFiles = [];

  public function __construct($attr, $data) {
    $this->initAttributes($attr);
    $this->processData($data);
    parent::__construct($this->template, $this->data);
  }

  /**
   * Init object template data
   * @param $attr
   */
  protected function initAttributes($attr) {
    $this->id         = $attr['id'];
    $this->class      = $attr["class"];
    $this->properties = $attr["properties"];
    $this->html       = $attr["html"];
    if (is_array($this->properties)) {
      foreach ($this->properties as $att => $val) {
        $this->prop .= " {$att} = '{$val}'";
      }
    }
  }

  /**
   * Process input data and object data
   * @param $data
   */
  protected function processData($data) {
    $this->data = array_replace_recursive($this->data, $data);
  }
}