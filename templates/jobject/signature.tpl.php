<div id="<?= $this->meta["id"] ?>" class="form-control kbw-signature" style="height: <?= $this->data["height"] ?>px;"></div>
<button id="<?= $this->meta["id"] ?>-clear" class="btn btn-info">Clear</button>
<script>
  $(function () {
    $("#<?= $this->meta["id"] ?>").signature();
    <?php  if($this->data["jsonValue"]){?>
    $("#<?= $this->meta["id"] ?>").signature("draw", <?= $this->data["jsonValue"] ?>);
    <?php }?>
    $("#<?= $this->meta["id"] ?>-clear").click(function () {
      $("#<?= $this->meta["id"] ?>").signature('clear');
    });
  });
</script>