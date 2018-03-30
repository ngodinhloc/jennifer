<?php
namespace thedaysoflife\view;

use jennifer\view\Base;
use thedaysoflife\sys\Configs;

class ViewBack extends Base {
  protected $title = Configs::SITE_TITLE;
  protected $description = Configs::SITE_DESCRIPTION;
  protected $keyword = Configs::SITE_KEYWORDS;
  protected $headerTemplate = "_header";
  protected $footerTemplate = "_footer";
  protected $requiredPermission = ["admin"];
  protected $admin;
}