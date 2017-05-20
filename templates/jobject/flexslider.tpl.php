<div class="col-md-12 slide">
  <div id="slider" class="flexslider">
    <ul class="slides list-unstyled">
      <?php foreach ($this->data["fullPhotos"] as $photo) { ?>
        <li><img src="<?= $photo ?>"></li>
      <?php } ?>
    </ul>
  </div>
  <?php if (sizeof($this->data["thumbPhotos"]) >= 2) { ?>
    <div id="carousel" class="flexslider">
      <ul class="slides list-inline">
        <?php foreach ($this->data["thumbPhotos"] as $thumb) { ?>
          <li><img src="<?= $thumb ?>"><span></span></li>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>
</div>
<?php if (sizeof($this->data["thumbPhotos"]) >= 2) { ?>
  <script type="text/javascript">
    $(window).load(function () {
      $('#carousel').flexslider({
                                  animation:     "slide",
                                  controlNav:    false,
                                  animationLoop: false,
                                  slideshow:     false,
                                  itemWidth:     75,
                                  itemMargin:    5,
                                  asNavFor:      '#slider'
                                });
      $('#slider').flexslider({
                                animation:     "slide",
                                smoothHeight:  true,
                                controlNav:    false,
                                animationLoop: false,
                                slideshow:     false,
                                sync:          "#carousel",
                                start:         function (slider) {
                                  $('body').removeClass('loading');
                                }
                              });
    });
  </script>
<?php } ?>