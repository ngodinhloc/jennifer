<?php

namespace Jennifer\Html;

use Jennifer\Template\Template;

/**
 * Class JObject: Jquery and Bootstrap Object class
 * @package Jennifer\Html
 */
class JObject
{
    /** @var  \Jennifer\Template\Template */
    protected $tpl;
    /** @var array list of templates */
    protected $templates = [];
    /** @var array data that will be used in templates */
    protected $data = [];
    /** @var array meta data : id, class, properties, innerHTML ... */
    protected $meta = [];
    /** @var array required meta files: css, javascript */
    protected $metaFiles = [];

    public function __construct($attr = [], $data = [])
    {
        $this->initMeta($attr);
        $this->processData($data);
    }

    /**
     * Init object meta data
     * @param $attr
     */
    protected function initMeta($attr)
    {
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
    protected function processData($data)
    {
        $this->data = array_replace_recursive($this->data, $data);
    }

    /**
     * Render html of object
     * @param bool $compress
     * @return string
     */
    public function render($compress = true)
    {
        $this->tpl = new Template($this->templates, $this->data, $this->meta);
        $html = $this->tpl->render($compress);

        return $html;
    }

    /**
     * @return Template
     */
    public function getTpl()
    {
        return $this->tpl;
    }

    /**
     * @param Template $tpl
     * @return JObject
     */
    public function setTpl(Template $tpl)
    {
        $this->tpl = $tpl;

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param array $templates
     * @return JObject
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return JObject
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     * @return JObject
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return array
     */
    public function getMetaFiles()
    {
        return $this->metaFiles;
    }

    /**
     * @param array $metaFiles
     * @return JObject
     */
    public function setMetaFiles(array $metaFiles)
    {
        $this->metaFiles = $metaFiles;

        return $this;
    }
}