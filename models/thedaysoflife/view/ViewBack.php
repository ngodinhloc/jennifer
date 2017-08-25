<?php
namespace thedaysoflife\view;
;
use view\Base;

class ViewBack extends Base {
  protected $title = SITE_TITLE;
  protected $description = SITE_DESCRIPTION;
  protected $keyword = SITE_KEYWORDS;
  protected $headerTemplate = "_header";
  protected $footerTemplate = "_footer";
  protected $requiredPermission = ["admin"];
  protected $admin;
}