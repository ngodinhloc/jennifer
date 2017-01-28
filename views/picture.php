<?php
use core\View;

define('PAGE_TITLE', 'The Picture Of Life');
$view = new View();
?>
<ul id="picture" class="row list-unstyled">
  <?php
    echo ($view->getPicture(0));
  ?>
</ul>
<div id="show-picture" class="show-more" data="<?php echo NUM_PER_PAGE; ?>">+ Load More Photos
</div>
<script>
  $(document).ready(function () {
    $("#picture").sortable({});
    $("#picture").disableSelection();
  });
  $(window).scroll(function () {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
      action = $("#show-picture").attr("class");
      if (action == 'show-more') {
        from = $('#picture>li').length;
        ajaxShowPicture(from);
      }
    }
  });
</script>