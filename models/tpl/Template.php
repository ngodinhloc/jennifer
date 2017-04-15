<?php
namespace tpl;

class Template {
  protected $template;
  protected $data;

  public function __construct($template, $data) {
    $this->template = $template;
    $this->data     = $data;
  }

  /**
   * Render templates
   * @param $tidy bool
   * @return string
   */
  public function render($tidy = true) {
    ob_start("ob_gzhandler");
    include_once(TEMPLATE_DIR . $this->template . TEMPLATE_EXT);
    $html = ob_get_clean();
    if ($tidy) {
      $html = $this->tidyHTML($html);
    }

    return $html;
  }

  /**
   * Remove white space between html tags
   * @param $html
   * @return string
   */
  protected function tidyHTML($html) {
    $html = preg_replace('/(?<=>)\s+(?=<)/', "", $html);

    return $html;
  }
}