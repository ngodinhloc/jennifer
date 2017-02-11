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
$json = json_encode($const, JSON_UNESCAPED_SLASHES);
?>
<script>
  var AJAX_LOADER = '<img id="loading-tiny" src="<?php echo SITE_URL;?>/interface/images/ajax-loader.gif"/>';
  CONST = $.parseJSON('<?php echo $json; ?>');
</script>