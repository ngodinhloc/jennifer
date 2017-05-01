<ul id="picture" class="list-unstyled">
  <?= $this->data["picture"] ?>
</ul>
<div id="show-picture" class="show-more" data="<?= NUM_PER_PAGE ?>">+ Load More Photos
</div>
<script>
  $(function () {
    $("#picture").sortable({});
    $("#picture").disableSelection();
  });
</script>