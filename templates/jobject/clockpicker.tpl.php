<div class="input-group clockpicker" data-autoclose="<?= $this->data["autoClose"] ?>">
  <input type="text" class="form-control <?= $this->meta["class"] ?>"
         id="<?= $this->meta["id"] ?>" name="<?= $this->meta["id"] ?>" value="<?= $this->data["value"] ?>" placeholder="hh:mm">
  <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
</div>
<script type="text/javascript">
  $(function () {
    $('.input-group.clockpicker').clockpicker();
  })
</script>