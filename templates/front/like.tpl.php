<ul id="slide-show" class="list-unstyled">
  <?= $this->data["days"] ?>
</ul>
<div id="show-more" class="show-more" order-tag="<?= $this->data["order"] ?>" data="<?= \thedaysoflife\sys\Configs::NUM_PER_PAGE *
                                                                                        2 ?>">
  + Load More Days
</div>
<script type="text/javascript">
  $(function () {
    wookmarkHandle();
  });
</script>