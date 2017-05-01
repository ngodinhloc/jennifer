<?php
use com\Com;

?>
<div class="row">
  <div id="div-day-content">
    <div class="form-group col-md-9">
      <label>When was your day? </label>
      <div>
        <select class=" form-control days" id="day" name="day">
          <option class="select" value="0">Day</option>
          <?php
          echo(Com::getDayDrop());
          ?>
        </select>
        <select class="form-control days" id="month" name="month">
          <option class="select" value="0">Month*</option>
          <?php
          echo(Com::getMonthDrop());
          ?>
        </select>
        <select class="form-control days" id="year" name="year">
          <option class="select" value="0">Year*</option>
          <?php
          echo(Com::getYearDrop());
          ?>
        </select>
        <span id="check" class="error"></span>
      </div>
    </div>
    <div class="form-group col-md-9">
      <label>Title or What happend on that day*</label>
      <input type="text" class="form-control" id="title" name="title"/>
    </div>
    <div class="form-group col-md-9">
      <label>Share your memory of that day*</label>
      <textarea class="form-control" rows="3" id="content" name="content"></textarea>
    </div>
  </div>
  <form id="form-upload-photos" class="no-front" name="form-upload-photos" role="form" enctype="multipart/form-data">
    <div class="form-group col-md-8 image">
      <label for="inputfile">Have some photos to upload ?</label>
      <input type="file" class="form-group" id="inputfile" name="inputfile[]" multiple="true" accept="image/jpeg,image/gif,image/png">
      <div class="body form-group">
        <p class="help-block">&nbsp</p>
        <p class="help-block" id="photo-loader">File size limit is 5MB</p>
      </div>
    </div>
  </form>
  <div class="no-front" id="div-author-info">
    <div class="form-group col-md-9 list-image">
      <ul id="sortable" class="list list-inline"></ul>
    </div>
    <div class="col-md-9 form-contact form-group">
      <div class="form-group registering  col-xs-12 col-sm-4">
        <div class="icon">
          <label class="full-name sr-only">Full Name</label>
        </div>
        <input type="text" class="full-name form-control" id="username" name="username" placeholder="Your name*">
      </div>

      <div class="form-group col-xs-12 col-sm-4">
        <div class="icon">
          <label class="email sr-only">Email address</label>
        </div>
        <input type="email" class="email form-control" id="email" name="email" placeholder="Your email address*">
      </div>
      <div class="form-group registering col-xs-12 col-sm-4">
        <div class="icon">
          <label class="website sr-only">Location</label>
        </div>
        <input type="text" class="website form-control" id="loc" name="loc" placeholder="Where you from">
      </div>
    </div>
    <div class="col-md-9 form-contact form-group">
      <div class="form-group registering  col-xs-12">
        <button type="submit" class="btn btn-primary" id="make-day">Submit Your Day</button>
        <span id="ajax-loader" class="error"></span>
      </div>
    </div>
  </div>
</div>
<script>
  $(".glyphicon-remove").live("click", function () {
    id = $(this).closest('li').attr('id');
    $(this).closest('li').remove();
  });
  $(function () {
    $('#content').autosize();
    $("#sortable").sortable({
      placeholder: "placeholder"
    });
    $("#sortable").disableSelection();

    $("#form-upload-photos").submit(function (e) {
      $("#photo-loader").html(AJAX_LOADER);
      var formObj = $(this);
      var upload = CONST.CONTROLLER_URL;
      if (window.FormData !== undefined) {	// for HTML5 browsers
        var formData = new FormData(this);
        formData.append("action", "uploadPhotos");
        formData.append("controller", "ControllerUpload");
        $.ajax({
          url:         upload,
          type:        'POST',
          data:        formData,
          mimeType:    "multipart/form-data",
          contentType: false,
          cache:       false,
          processData: false,
          success:     function (data, textStatus, jqXHR) {
            $("#photo-loader").html('Drag photo to change order.');
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
          $("#photo-loader").html('Drag photo to change order.');
          $("#sortable").append(data);
        });
      }
    });

    $("#inputfile").change(function () {
      $("#form-upload-photos").submit();
    });
  });
</script>