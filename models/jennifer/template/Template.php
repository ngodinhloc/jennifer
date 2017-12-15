<?php
namespace jennifer\template;

use jennifer\com\Compressor;

/**
 * Class Template
 * @package template
 */
class Template implements TemplateInterface {
  /** @var array list of templates */
  protected $templates = [];
  /** @var array data that will be used in templates */
  protected $data = [];
  /** @var array meta data : id, class, properties... */
  protected $meta = [];

  public function __construct($templates = [], $data = [], $meta = []) {
    if (!is_array($templates)) {
      $templates = [$templates];
    }
    $this->templates = $templates;
    $this->data      = $data;
    $this->meta      = $meta;
  }

  /**
   * Render template
   * @param $compress bool
   * @return string
   */
  public function render($compress = true) {
    ob_start();
    foreach ($this->templates as $template) {
      include(TEMPLATE_DIR . $template . TEMPLATE_EXT);
    }
    $html = ob_get_clean();
    if ($compress) {
      $html = Compressor::compressHTML($html);
    }

    return $html;
  }
}