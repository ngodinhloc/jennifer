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
</script>