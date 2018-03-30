<div id="search-container">
  <?= $this->data["searchResult"] ?>
  <img id="loading-tiny" src="<?= \thedaysoflife\sys\Configs::SITE_URL ?>/interface/images/ajax-loader.gif" class="hidden"/>
</div>
<script type="text/javascript">
  $(function () {
    wookmarkHandle();
    $("#main-search-text").val('<?= $this->data["searchTerm"] ?>');
    $("#top-search-text").val('<?= $this->data["searchTerm"] ?>');
  });
</script>