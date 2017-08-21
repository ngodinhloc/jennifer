<?php
$const = ["SITE_URL"       => SITE_URL,
          "CONTROLLER_URL" => CONTROLLER_URL,
          "LIST_URL"       => SITE_URL . "/day/",
          "EDIT_URL"       => SITE_URL . "/edit/",
          "LIST_EXT"       => URL_EXT,
          "NUM_PER_PAGE"   => NUM_PER_PAGE,
          "NUM_CALENDAR"   => NUM_CALENDAR,
          "NUM_PICTURE"    => NUM_PICTURE,
          "LIST_FADE"      => 1000,
          "COM_FADE"       => 500,
          "LOADER_FADE"    => 3000];
$json  = json_encode($const, JSON_UNESCAPED_SLASHES);
?>
  <script>
    var AJAX_LOADER = '<img id="loading-tiny" src="<?= SITE_URL ?>/interface/images/ajax-loader.gif"/>';
    var CONST = $.parseJSON('<?= $json ?>');
  </script>
  <script type="text/javascript" src="<?php echo SITE_URL ?>/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL ?>/plugins/bootstrap/bootstrap.bootbox.min.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL ?>/plugins/jquery/jquery.scrolltofixed.min.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL ?>/plugins/jquery/jquery.imagesloaded.min.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL ?>/plugins/jquery/jquery.wookmark.min.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL ?>/plugins/jquery/jquery.easing.min.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL ?>/plugins/jquery/jquery.mousewheel.min.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL ?>/js/ajax.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL ?>/js/thedaysoflife.front.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL ?>/js/thedaysoflife.back.js"></script>
<?= $this->meta["metaTags"]["footer"] ?>