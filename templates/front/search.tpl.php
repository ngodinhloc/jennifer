<div id="search-container">
  <?php
  if (isset($this->data["searchResult"])) {
    echo($this->data["searchResult"]);
  }
  ?>
  <img id="loading-tiny" src="<?php echo SITE_URL ?>/interface/images/ajax-loader.gif" class="hidden"/>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    wookmarkHandle();
    $("#main-search-text").val('<?php echo $this->data["searchTerm"]; ?>');
    $("#top-search-text").val('<?php echo $this->data["searchTerm"]; ?>');
  });
</script>