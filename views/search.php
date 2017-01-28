<?php
use core\View;
use sys\System;

$search = System::getSearchPara();
?>
<div id="search-container">
  <?php
  if ($search != "") {
    $view = new View();
    echo($view->getSearch($search));
  }
  ?>
  <img id="loading-tiny" src="<?php echo SITE_URL ?>/interface/images/ajax-loader.gif" class="hidden"/>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    wookmarkHandle();
    $("#main-search-text").val('<?php echo $search; ?>');
    $("#top-search-text").val('<?php echo $search; ?>');
  });
  $(window).scroll(function () {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
      action = $("#search-more").attr("class");
      if (action == 'show-more') {
        search = $('#main-search-text').val();
        from = $('#slide-show>li.item').length;
        ajaxSearchMore(search, from);
      }
    }
  });
</script>