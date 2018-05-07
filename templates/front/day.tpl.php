<? include_once('include/facebook.inc.php'); ?>
<div class="row">
  <div class="col-md-12 col-md-8 no-front">
    <? if (isset($this->data["day"])) { ?>
      <h4 class="title">
        <?= htmlspecialchars($this->data["day"]['title']) ?>
        <input type="hidden" id="day-id" value="<?= $this->data["day"]['id'] ?>">
      </h4>
      <div class="post-meta">
        <div class="author post">
          <i class="icon"></i>
          <span><a href="<?= htmlspecialchars($this->data["day"]["authorLink"]) ?>"><?= htmlspecialchars($this->data["day"]['username']) ?></a></span>
          <? if ($this->data["day"]['locationLink']) { ?>
            -
            <a href="<?= htmlspecialchars($this->data["day"]["locationLink"]) ?>"><?= htmlspecialchars($this->data["day"]['location']) ?></a>
          <? } ?>
        </div>
        <div class="date post">
          <i class="icon"></i>
          <span><a href="<?= htmlspecialchars($this->data["day"]["dateLink"]) ?>"><?= $this->data["day"]["time"] ?></a></span>
        </div>
        <div class="stat post">
        <span class="view">
          <a href="javascript:void(0)" class="comment-count" id="count-<?= $this->data["day"]["id"] ?>">
            <i class="icon"></i><?= number_format($this->data["day"]['count']) ?>
          </a>
        </span>
        </div>
      </div>
      <hr/>
      <div class="body">
        <p><?= htmlspecialchars($this->data["day"]['content']) ?></p>
      </div>
      <?= $this->data["day"]["slider"] ?>
      <div class="action-content" id="action-container">
        <div class="stat">
          <? if ($this->data["day"]["liked"]) { ?>
            <span class="like liked" title="Liked">
              <i class="icon"></i><?= number_format($this->data["day"]['like']) ?>
            </span>
          <? }
          else { ?>
            <span class="like" title="Like">
                  <a href="javascript:void(0)" class="like-day" data-id="<?= $this->data["day"]["id"] ?>" data-like="<?= $this->data["day"]["like"] ?>">
                    <i class="icon"></i><?= number_format($this->data["day"]['like']) ?>
                  </a>
                </span>
          <? } ?>
          <span class="reply">
            <a href="javascript:void(0)" class="reply-focus"><i class="icon"></i>Reply</a></span>
        </div>
        <div class="social-content">
          <div class="fb-like" data-href="<?= $this->data["day"]["url"] ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
        </div>
      </div>
      <div class="comment" id="comment-container">
        <?= $this->data["day"]["comments"] ?>
      </div>
      <div class="form-contact form-group media comment">
        <h4>Share your thought</h4><br/>
        <span class="user-icon pull-left"></span>
        <div class="media-body comment-form" id="div-comment-content">
          <div class="form-contact">
            <div class="form-group col-md-12">
              <textarea class="form-control comment-text" id="content" name="content" required></textarea>
            </div>
          </div>
          <div class="form-group col-md-4">
            <div class="icon">
              <label class="full-name sr-only">Full Name</label>
            </div>
            <input type="text" id="username" name="username" class="full-name form-control" placeholder="Your name*" required/>
          </div>
          <div class="form-group col-md-4">
            <div class="icon">
              <label class="email sr-only">Email address</label>
            </div>
            <input type="email" id="email" name="email" class="email form-control" placeholder="Your email address*" required/>
          </div>
          <div class="form-group col-md-4 registering">
            <button type="button" class="btn btn-primary" id="submit">Submit</button>
            <span id="ajax-loader"></span>
          </div>
        </div>
      </div>
      <div class="clear-both"></div>
    <? }
    else { ?>
      <h4>Day not found</h4>
    <? } ?>
  </div>
  <div class="col-xs-12 col-md-4">
    <?php include_once('include/right.inc.php'); ?>
  </div>
</div>
<script type="text/javascript">
  $(function () {
    $('.comment-text').autosize();
    $(".comment-count").live("click", function () {
      scrollTo("#action-container");
    });
  });
</script>