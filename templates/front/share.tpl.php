<?php use com\Common; ?>
<div class="row">
  <div id="div-day-content">
    <div class="form-group col-md-9">
      <label>When was your day? </label>
      <div>
        <select class=" form-control days" id="day" name="day">
          <option class="select" value="0">Day</option>
          <?= Common::getDayDrop() ?>
        </select>
        <select class="form-control days" id="month" name="month">
          <option class="select" value="0">Month*</option>
          <?= Common::getMonthDrop() ?>
        </select>
        <select class="form-control days" id="year" name="year">
          <option class="select" value="0">Year*</option>
          <?= Common::getYearDrop() ?>
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
  <div class="form-group col-md-9">
    <?= $this->data["photoUploader"] ?>
  </div>
  <div class="no-front" id="div-author-info">
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
  $(function () {
    $('#content').autosize();
  });
</script>