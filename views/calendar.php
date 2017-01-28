<?php
use core\View;

define('PAGE_TITLE', 'The Calendar Of Life');
$view = new View();
?>
<div id="calendar-container">
  <?php
  echo ($view->getCalendar(0));
  ?>
</div>
<div id="show-calendar" class="show-more" data="<?php echo NUM_CALENDAR; ?>">+ Load More Days
</div>
<script>
  $(function () {
    $(window).scroll(function () {
      if ($(window).scrollTop() == $(document).height() - $(window).height()) {
        action = $("#show-calendar").attr("class");
        if (action == 'show-more') {
          from = $('div.calendar-div').length;
          ajaxShowCalendar(from);
        }
      }
    });
  });
</script>
