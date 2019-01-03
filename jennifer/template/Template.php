<?php

namespace jennifer\template;

use jennifer\com\Compressor;
use jennifer\sys\Config;
use jennifer\sys\Globals;

/**
 * Class Template
 * @package jennifer\template
 */
class Template implements TemplateInterface
{
    /** @var array list of templates */
    protected $templates = [];
    /** @var array data that will be used in templates */
    protected $data = [];
    /** @var array meta data : id, class, properties... */
    protected $meta = [];

    public function __construct($templates = [], $data = [], $meta = [])
    {
        if (!is_array($templates)) {
            $templates = [$templates];
        }
        $this->templates = $templates;
        $this->data = $data;
        $this->meta = $meta;
    }

    /**
     * Render template
     * @param $compress bool
     * @return string
     */
    public function render($compress = true)
    {
        ob_start();
        foreach ($this->templates as $template) {
            $file = Globals::docRoot() . "/" . Config::getConfig("TEMPLATE_DIR") . $template . Config::getConfig("TEMPLATE_EXT");
            include($file);
        }
        $html = ob_get_clean();
        if ($compress) {
            $html = Compressor::compressHTML($html);
        }

        return $html;
    }

    /**
     * Escape output string before rendering
     * @param string $output
     * @return string
     */
    public function escape($output = "")
    {
        return htmlspecialchars($output, ENT_COMPAT, "UTF-8");
    }
}