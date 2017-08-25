<form id="form-upload-photos" class="no-front" name="form-upload-photos" role="form" enctype="multipart/form-data">
  <div class="row form-group col-sm-12 image">
    <label for="inputfile"><?= $this->data["text"] ?></label>
    <input type="file" class="form-group" id="inputfile" name="inputfile[]" multiple="true" accept="<?= $this->data["accept"] ?>">
    <div class="body form-group">
      <p class="help-block">&nbsp</p>
      <p class="help-block" id="photo-loader">File size limit is <?= $this->data["maxSize"] ?>MB</p>
    </div>
  </div>
  <div class="row col-sm-12 list-image ">
    <ul id="sortable" class="list list-inline">
      <?php
      if (sizeof($this->data["currentPhotos"] > 0)) {
        foreach ($this->data["currentPhotos"] as $photo) {
          print('<li id="' . $photo["id"] . '">
                      <div class="img-wrapper">
                      <img src="' . $photo["thumb"] . '" class="photo-thumb"/>
                      <span class="glyphicon glyphicon-remove"></span>
                      </div>
                    </li>');
        }
      }
      ?>
    </ul>
  </div>
</form>
<script>
  $(function () {
    $("#sortable").sortable({placeholder: "placeholder"});
    $("#sortable").disableSelection();

    $(".glyphicon-remove").live("click", function () {
      id = $(this).closest('li').attr('id');
      $(this).closest('li').remove();
    });

    $("#form-upload-photos").submit(function (e) {
      $("#photo-loader").html(AJAX_LOADER);
      var formObj = $(this);
      var upload = CONST.CONTROLLER_URL;
      if (window.FormData !== undefined) {	// for HTML5 browsers
        var formData = new FormData(this);
        formData.append("action", "<?= $this->data["action"] ?>");
        formData.append("controller", "<?= $this->data["controller"] ?>");
        $.ajax({
          url:         upload,
          type:        'POST',
          data:        formData,
          mimeType:    "multipart/form-data",
          contentType: false,
          cache:       false,
          processData: false,
          success:     function (data, textStatus, jqXHR) {
            $("#photo-loader").html('<?= $this->data["drag"] ?>');
            $("#sortable").append(data);
          },
          error:       function (jqXHR, textStatus, errorThrown) {
          }
        });
        e.preventDefault();
        // e.unbind();
      }
      else {	//for older browsers
        var iframeId = 'unique' + (new Date().getTime());
        var iframe = $('<iframe src="javascript:false;" name="' + iframeId + '" />');
        iframe.hide();
        formObj.attr('target', iframeId);
        iframe.appendTo('body');
        iframe.load(function (e) {
          var doc = getDoc(iframe[0]);
          var docRoot = doc.body ? doc.body : doc.documentElement;
          var data = docRoot.innerHTML;
          $("#photo-loader").html('<?= $this->data["drag"] ?>');
          $("#sortable").append(data);
        });
      }
    });
    $("#inputfile").change(function () {
      $("#form-upload-photos").submit();
    });
  });
</script>