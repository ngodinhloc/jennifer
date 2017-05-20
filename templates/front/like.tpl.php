<ul id="slide-show" class="list-unstyled">
  <?= $this->data["days"] ?>
</ul>
<div id="show-more" class="show-more" order-tag="<?= $this->data["order"] ?>" data="<?= NUM_PER_PAGE * 2 ?>">
  + Load More Days
</div>
<script type="text/javascript">
  $(function () {
    wookmarkHandle();
  });
</script>