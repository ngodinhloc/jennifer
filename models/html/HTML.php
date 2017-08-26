<?php
  namespace html;
  /**
   * Class HTML: This class is used to create HTML element (so that we no longer print HTML code inside models)
   * @package html
   */
  class HTML implements HTMLInterface {
    private $tag;
    private $id;
    private $name;
    private $class;
    private $propList = [];
    private $innerHTML;

    public function __construct() {
    }

    /**
     * Set object attributes
     * @param string $id
     * @param string $name
     * @param string $class
     * @param string $propList any attributes or properties other than id, name, class
     * @param string $innerHTML
     * @return $this
     */
    public function setAttribute($id = null, $name = null, $class = null, $propList = null, $innerHTML = null) {
      $this->id = $id;
      $this->name = $name;
      $this->class = $class;
      $this->propList = $propList;
      $this->innerHTML = $innerHTML;

      return $this;
    }

    /**
     * Unset object attributes
     */
    private function unsetAttribute() {
      $this->id = null;
      $this->name = null;
      $this->class = null;
      $this->propList = null;
      $this->innerHTML = null;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function setTag($tag) {
      $this->tag = $tag;
      $this->unsetAttribute();

      return $this;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setID($id) {
      $this->id = $id;

      return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name) {
      $this->name = $name;

      return $this;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setClass($class) {
      $this->class = $class;

      return $this;
    }

    /**
     * @param array $prop
     * @return $this
     */
    public function setProp($prop) {
      $this->propList = $prop;

      return $this;
    }

    /**
     * @param string $innerHTML
     * @return $this
     */
    public function setInnerHTML($innerHTML) {
      $this->innerHTML = $innerHTML;

      return $this;
    }

    /**
     * Open HTML tag
     * @return string
     */
    public function open() {
      $html = "<{$this->tag}{$this->initID()}{$this->initName()}{$this->initClass()}{$this->initProp()}>";

      return $html;
    }

    /**
     * Close HTML element
     * @return string
     */
    public function close() {
      $html = "</{$this->tag}>";

      return $html;
    }

    /**
     * Create the element
     * @return string
     */
    public function create() {
      $innerHTML = isset($this->innerHTML) ? $this->innerHTML : "";
      $html = $this->open() . $innerHTML . $this->close();

      return $html;
    }

    /**
     * Init name
     * @return string
     */
    private function initName() {
      $name = isset($this->name) ? " name ='{$this->name}'" : "";

      return $name;
    }

    /**
     * Init ID
     * @return string
     */
    private function initID() {
      $id = isset($this->id) ? " id ='{$this->id}'" : "";

      return $id;
    }

    /**
     * Init class
     * @return string
     */
    private function initClass() {
      $class = isset($this->class) ? " class ='{$this->class}'" : "";

      return $class;
    }

    /**
     * Init properties
     * @return string
     */
    private function initProp() {
      $prop = "";
      if (is_array($this->propList)) {
        foreach ($this->propList as $att => $val) {
          $prop .= " {$att} = '{$val}'";
        }
      }

      return $prop;
    }
  }