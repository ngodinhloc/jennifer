<?php
  namespace tpl;
use com\Compressor;

  class Template implements TemplateInterface {
    protected $template;
    protected $data;

    public function __construct($template, $data) {
      $this->template = $template;
      $this->data = $data;
    }

    /**
     * Render templates
     * @param $compress bool
     * @return string
     */
    public function render($compress = true) {
      ob_start("ob_gzhandler");
      include_once(TEMPLATE_DIR . $this->template . TEMPLATE_EXT);
      $html = ob_get_clean();
      if ($compress) {
        $html = Compressor::compressHTML($html);
      }

      return $html;
    }
  }