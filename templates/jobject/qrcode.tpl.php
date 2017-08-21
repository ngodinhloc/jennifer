<div id="<?= $this->meta["id"] ?>"></div>
<script>
  $(function () {
    var options = {
      render:     'image',
      minVersion: 1,
      maxVersion: 40,
      ecLevel:    'H',
      left:       0,
      top:        0,
      size:       <?= $this->data["size"] ?>,
      text:       '<?= $this->data["text"] ?>',
      quiet:      <?= $this->data["border"] ?>,
      fill:       '#000',
      background: '<?= $this->data["background"] ?>',
      radius:     0,
      mode:       0,
      mSize:      0.1,
      mPosX:      0.5,
      mPosY:      0.5,
      label:      'no label',
      fontname:   'sans',
      fontcolor:  '#000',
      image:      null
    }
    $("#<?= $this->meta["id"] ?>").qrcode(options);
  });
</script>