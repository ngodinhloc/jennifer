<div id="calendar-container">
  <?php echo($this->data["calendar"]); ?>
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
