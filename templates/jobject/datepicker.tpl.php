<div class="input-group date">
  <input type="text" class="form-control <?= $this->class ?>" id="<?= $this->id ?>" name="<?= $this->id ?>"
         value="<?= $this->data["value"] ?>" placeholder="dd/mm/yyyy">
  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>
<script>
  $(function () {
    $('.input-group.date').datepicker({
      todayBtn:       "linked",
      todayHighlight: true,
      autoclose:      <?= $this->data["autoClose"] ?>,
      format:         "dd/mm/yyyy",
      startDate:      "<?= $this->data["startDate"] ?>",
      endDate:        "<?= $this->data["endDate"] ?>",
      forceParse:     false,
    });
  })
</script>