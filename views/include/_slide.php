<div class="slideshow container-fluid region" style="padding: 0;">
  <div class="col-xs-12" style="padding: 0;">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <div class="item active">
          <img width="1921" height="321" alt="Second slide" data-src="" src="<?php echo SITE_URL ?>/interface/images/bg_slide1.jpg">
        </div>
      </div>
    </div>
  </div>
  <div class="form-search">
    <input type="text" id="main-search-text" class="input-medium search-query" placeholder="" value="<?php if ($search != "") {
      echo $search;
    } ?>"/>
    <button type="button" id="main-search-button" class="btn btn-lg btn-primary">Search</button>
  </div>
</div>