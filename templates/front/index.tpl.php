<ul id="slide-show" class="list-unstyled">
  <?php
  echo($this->data["days"]);
  ?>
</ul>
<div id="show-more" class="show-more" order-tag="<?php echo $this->data["order"]; ?>" data="<?php echo NUM_PER_PAGE * 2; ?>">
  + Load More Days
</div>
<script type="text/javascript">
  $(document).ready(function () {
    wookmarkHandle();
  });
  $(window).scroll(function () {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
      action = $("#show-more").attr("class");
      if (action == 'show-more') {
        from = $('#slide-show>li.item').length;
        order = $("#show-more").attr("order-tag");
        ajaxShowDay(from, order);
      }
    }
  });
</script>