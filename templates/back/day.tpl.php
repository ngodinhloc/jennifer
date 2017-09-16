<div class="admin-content" id="edit-day">
  <div class="row no-front">
    <div class="form-group col-md-9">
      <label>When was your day? </label>
      <div>
        <select class=" form-control days" id="day" name="day">
          <option class="select" value="0">Day</option>
          <?= $this->data["daySelect"] ?>
        </select>
        <select class="form-control days" id="month" name="month">
          <option class="select" value="0">Month*</option>
          <?= $this->data["monthSelect"] ?>
        </select>
        <select class="form-control days" id="year" name="year">
          <option class="select" value="0">Year*</option>
          <?= $this->data["yearSelect"] ?>
        </select>
        <span id="check" class="error"></span>
      </div>
    </div>
    <div class="form-group col-md-9">
      <label>What happend on that day ?</label>
      <input type="hidden" id="id" name="id" value="<?= $this->data["row"]["id"] ?>"/>
      <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($this->data["row"]['title']) ?>"/>
    </div>
    <div class="form-group col-md-9">
      <label>Like:</label>
      <input type="number" class="form-control" id="like" name="like" size="5" value="<?= $this->data["row"]['like'] ?>"/>
    </div>

    <div class="form-group col-md-9">
      <label>Share your memories of that day</label>
      <textarea class="form-control" rows="3" id="content" name="content"><?= stripcslashes($this->data["row"]['content']) ?></textarea>
    </div>
    <div class="form-group col-md-9">
      <?= $this->data["photoUploader"] ?>
    </div>
    <div class="col-md-10 form-contact form-group">
      <div class="form-group registering  col-xs-12 col-sm-4">
        <div class="icon">
          <label class="full-name sr-only">Full Name</label>
        </div>
        <input type="text" class="full-name form-control" id="username" name="username" value="<?= htmlspecialchars($this->data["row"]['username']) ?>">
      </div>

      <div class="form-group col-xs-12 col-sm-4">
        <div class="icon">
          <label class="email sr-only">Email address</label>
        </div>
        <input type="email" class="email form-control" id="email" name="email" value="<?= htmlspecialchars($this->data["row"]['email']) ?>">
      </div>
      <div class="form-group registering col-xs-12 col-sm-4">
        <div class="icon">
          <label class="website sr-only">Location</label>
        </div>
        <input type="text" class="website form-control" id="loc" name="loc" value="<?= htmlspecialchars($this->data["row"]['location']) ?>">
      </div>
    </div>
    <div class="col-md-10 form-contact form-group">
      <div class="form-group registering  col-xs-12">
        <button type="button" class="btn btn-primary" id="update-day">Update Day</button>
        <span id="ajax-loader" class="error"></span>
      </div>
    </div>
  </div>
</div>
<div id="confirm"></div>
<script>
  $(function () {
    CKEDITOR.replace('content', {customConfig: 'config_admin.js'});
  });
</script>