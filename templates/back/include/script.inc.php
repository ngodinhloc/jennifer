<?php
$const = ["SITE_URL"       => \thedaysoflife\sys\Configs::SITE_URL,
          "CONTROLLER_URL" => \thedaysoflife\sys\Configs::CONTROLLER_URL,
          "LIST_URL"       => \thedaysoflife\sys\Configs::SITE_URL . "/day/",
          "EDIT_URL"       => \thedaysoflife\sys\Configs::SITE_URL . "/edit/",
          "LIST_EXT"       => \thedaysoflife\sys\Configs::URL_EXT,
          "NUM_PER_PAGE"   => \thedaysoflife\sys\Configs::NUM_PER_PAGE,
          "NUM_CALENDAR"   => \thedaysoflife\sys\Configs::NUM_CALENDAR,
          "NUM_PICTURE"    => \thedaysoflife\sys\Configs::NUM_PICTURE,
          "LIST_FADE"      => 1000,
          "COM_FADE"       => 500,
          "LOADER_FADE"    => 3000];
$json  = json_encode($const, JSON_UNESCAPED_SLASHES);
?>
  <script>
    var AJAX_LOADER = '<img id="loading-tiny" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/interface/images/ajax-loader.gif"/>';
    var CONST = $.parseJSON('<?= $json ?>');
  </script>
  <script type="text/javascript" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/plugins/bootstrap/bootstrap.bootbox.min.js"></script>
  <script type="text/javascript" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/plugins/jquery/jquery.scrolltofixed.min.js"></script>
  <script type="text/javascript" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/plugins/jquery/jquery.imagesloaded.min.js"></script>
  <script type="text/javascript" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/plugins/jquery/jquery.wookmark.min.js"></script>
  <script type="text/javascript" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/plugins/jquery/jquery.easing.min.js"></script>
  <script type="text/javascript" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/plugins/jquery/jquery.mousewheel.min.js"></script>
  <script type="text/javascript" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/js/ajax.js"></script>
  <script type="text/javascript" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/js/thedaysoflife.front.js"></script>
  <script type="text/javascript" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/js/thedaysoflife.back.js"></script>
<?= $this->meta["metaTags"]["footer"] ?>