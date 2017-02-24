<div class="row">
  <div class="col-md-12 header">
    <div class="container">
      <header class="navbar navbar-static-top bs-docs-nav menu-top" id="top" role="banner">
        <? include('_menu.inc.php'); ?>
      </header>
      <div class="col-xs-6 wrapper clearfix">
        <a class="logo navbar-btn pull-left" title="Home" href="<?php echo SITE_URL ?>">
          <img alt="Home" src="<?php echo SITE_URL ?>/interface/images/logo.png">
        </a>
        <span class="slogan">Share Memories, Inspire People</span>
      </div>

      <div class="col-xs-2 reggion">
        <div class="button_list">
          <a href="<?php echo SITE_URL ?>/share/" class="btn btn-lg btn-primary" type="button">Share Your Day</a>
        </div>
      </div>
      <div class="form-search">
        <input type="text" id="top-search-text" class="input-medium search-query" placeholder="Search days"
               value="<?php if ($this->data["search"] != "") {echo $this->data["search"];} ?>"/>
        <button type="button" id="top-search-button" class="btn btn-lg btn-primary">Search</button>
      </div>
    </div>
  </div>
</div>