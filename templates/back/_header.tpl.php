<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php include_once('include/_meta.inc.php'); ?>
</head>
<body class="html front not-logged-in no-sidebars page-node">
<? include_once('include/_ga.inc.php'); ?>
<div id="page-wrapper">
  <div id="page">
    <div class="col-xs-12 container-fluid region">
      <div class="row">
        <div class="col-md-12 header">
          <div class="container">
            <header class="navbar navbar-static-top bs-docs-nav menu-top" id="top" role="banner">
              <?
              if ($this->view != DEFAULT_VIEW) {
                include('include/_menu.inc.php');
              }
              ?>
            </header>
            <div class="col-xs-6 wrapper clearfix">
              <a class="logo navbar-btn pull-left" title="Home" href="<?= SITE_URL ?>">
                <img alt="Home" src="<?= SITE_URL ?>/interface/images/logo.png">
              </a>
            </div>
            <div class="col-xs-2 reggion">
              <div class="button_list">
                <a href="<?= SITE_URL ?>/share/" class="btn btn-lg btn-primary" type="button">Share Your Day</a>
              </div>
            </div>
            <form class="form-search">
              <input type="text" class="input-medium search-query">
              <button type="submit" class="btn btn-lg btn-primary">Search</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="dashboard container-fluid region" style="padding: 0;">
      <img class="img-rounded"/>
    </div>
    <div class="main_container region">
      <header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
        <?
        if ($this->view != DEFAULT_VIEW) {
          include('include/_menu.inc.php');
        }
        ?>
      </header>