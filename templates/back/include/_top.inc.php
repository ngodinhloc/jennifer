<div class="row">
  <div class="col-md-12 header">
    <div class="container">
      <header class="navbar navbar-static-top bs-docs-nav menu-top" id="top" role="banner">
        <?
        if ($this->view != 'login') {
          include('_menu.inc.php');
        }
        ?>
      </header>
      <div class="col-xs-6 wrapper clearfix">
        <a class="logo navbar-btn pull-left" title="Home" href="<?php echo SITE_URL ?>">
          <img alt="Home" src="<?php echo SITE_URL ?>/interface/images/logo.png">
        </a>
      </div>
      <div class="col-xs-2 reggion">
        <div class="button_list">
          <a href="<?php echo SITE_URL ?>/share/" class="btn btn-lg btn-primary" type="button">Share Your Day</a>
        </div>
      </div>
      <form class="form-search">
        <input type="text" class="input-medium search-query">
        <button type="submit" class="btn btn-lg btn-primary">Search</button>
      </form>
    </div>
  </div>
</div>