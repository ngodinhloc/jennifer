<div id="<?= $this->id ?>" class="form-control kbw-signature" style="height: <?= $this->data["height"] ?>px;"></div>
<button id="<?= $this->id ?>-clear" class="btn btn-info">Clear</button>
<script>
  $(function () {
    $("#<?= $this->id ?>").signature();
    <?php  if($this->data["jsonValue"]){?>
    $("#<?= $this->id ?>").signature("draw", <?= $this->data["jsonValue"] ?>);
    <?php }?>
    $("#<?= $this->id ?>-clear").click(function () {
      $("#<?= $this->id ?>").signature('clear');
    });
  });
</script>