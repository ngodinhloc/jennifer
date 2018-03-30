<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> <?php include_once('include/meta.inc.php'); ?>
</head>
<body class="html front not-logged-in no-sidebars page-node">
<? include_once('include/ga.inc.php'); ?>
<div id="page-wrapper">
  <div id="page">
    <div class="col-xs-12 container-fluid region">
      <div class="row">
        <div class="col-md-12 header">
          <div class="container">
            <header class="navbar navbar-static-top bs-docs-nav menu-top" id="top" role="banner">
              <? include('include/menu.inc.php'); ?>
            </header>
            <div class="col-xs-6 wrapper clearfix">
              <a class="logo navbar-btn pull-left" title="Home" href="<?= \thedaysoflife\sys\Configs::SITE_URL ?>">
                <img alt="Home" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/interface/images/logo.png">
              </a>
              <span class="slogan">Share Memories, Inspire People</span>
            </div>
            <div class="col-xs-2 reggion">
              <div class="button_list">
                <a href="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/share/" class="btn btn-lg btn-primary" type="button">Share Your Day</a>
              </div>
            </div>
            <div class="form-search">
              <input type="text" id="top-search-text" class="input-medium search-query" placeholder="Search days"
                     value="<?= $this->data["searchTerm"] ?>"/>
              <button type="button" id="top-search-button" class="btn btn-lg btn-primary">Search</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="slideshow container-fluid region" style="padding: 0;">
      <div class="main-search col-sm-5 input-group">
        <input type="text" id="main-search-text" class="input-medium search-query form-control" placeholder=""
               value="<?= $this->data["searchTerm"] ?>"/>
        <span class="input-group-btn">
          <button type="button" id="main-search-button" class="btn btn-primary">Search</button>
        </span>
      </div>
    </div>
    <div class="main_container region">
      <header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
        <? include('include/menu.inc.php'); ?>
      </header>
      <div id="show-content" class="row row-offcanvas row-offcanvas-right">