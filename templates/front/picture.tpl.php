<ul id="picture" class="list-unstyled">
  <?php
  echo($this->data["picture"]);
  ?>
</ul>
<div id="show-picture" class="show-more" data="<?php echo NUM_PER_PAGE; ?>">+ Load More Photos
</div>
<script>
  $(document).ready(function () {
    $("#picture").sortable({});
    $("#picture").disableSelection();
  });
</script>