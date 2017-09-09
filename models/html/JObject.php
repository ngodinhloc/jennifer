<?php
namespace html;

use template\Template;

/**
 * Class JObject: Jquery and Bootstrap Object class
 * @package html
 */
class JObject {
  /** @var  \template\Template */
  protected $tpl;
  /** @var array list of templates */
  protected $templates = [];
  /** @var array data that will be used in templates */
  protected $data = [];
  /** @var array meta data : id, class, properties, innerHTML ... */
  protected $meta = [];
  /** @var array required meta files: css, javascript */
  public $metaFiles = [];

  public function __construct($attr = [], $data = []) {
    $this->initMeta($attr);
    $this->processData($data);
  }

  /**
   * Render html of object
   * @param bool $compress
   * @return string
   */
  public function render($compress = true) {
    $this->tpl = new Template($this->templates, $this->data, $this->meta);
    $html      = $this->tpl->render($compress);

    return $html;
  }

  /**
   * Init object meta data
   * @param $attr
   */
  protected function initMeta($attr) {
    if (isset($attr["id"])) {
      $this->meta["id"] = $attr["id"];
    }

    if (isset($attr["class"])) {
      $this->meta["class"] = $attr["class"];
    }

    if (isset($attr["html"])) {
      $this->meta["html"] = $attr["html"];
    }

    if (isset($attr["properties"])) {
      $this->meta["properties"] = $attr["properties"];
      if (is_array($this->meta["properties"])) {
        foreach ($this->meta["properties"] as $att => $val) {
          $this->meta["prop"] .= " {$att} = '{$val}'";
        }
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