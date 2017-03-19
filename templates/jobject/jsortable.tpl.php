<ul id="<?= $this->id; ?>" class="row list-unstyled <?= $this->class; ?>">
  <?= $this->list; ?>
</ul>
<script>
  $(document).ready(function () {
    id = "#" + <?=$this->id;?>;
    $(id).sortable({});
    $(id).disableSelection();
  });
</script>