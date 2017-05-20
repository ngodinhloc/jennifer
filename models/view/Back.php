<?php
namespace view;
use view\Base;

class Back extends Base {
  protected $headerTemplate = "_header";
  protected $footerTemplate = "_footer";
  protected $requiredPermission = ["admin"];
}