<?php
namespace thedaysoflife\view;
use view\Base;

class ViewFront extends Base {
  protected $title = SITE_TITLE;
  protected $description = SITE_DESCRIPTION;
  protected $keyword = SITE_KEYWORDS;
  protected $headerTemplate = "_header";
  protected $footerTemplate = "_footer";
  protected $user;
}