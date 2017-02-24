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
      <? include_once('include/_top.inc.php'); ?>
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