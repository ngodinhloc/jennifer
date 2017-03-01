<?php use com\Com; ?>
<? include_once('include/_facebook.inc.php'); ?>
<div class="row">
  <div class="col-md-12 col-md-8 no-front">
    <?php if (isset($this->data["days"]['id'])) { ?>
      <h4 class="title">
        <?php echo $this->data["days"]['day'] . '/' . $this->data["days"]['month'] . '/' . $this->data["days"]['year'] .
                   ': ' . $this->data["days"]['title']; ?>
        <input type="hidden" id="day-id" value="<?php echo $this->data["days"]['id']; ?>">
      </h4>
      <div class="post-meta">
        <div class="author post">
          <i class="icon"></i>
          <?php
          $searchAuthor = SITE_URL . "/search/tag=" . urlencode($this->data["days"]['username']);
          $searchLoc    = SITE_URL . "/search/tag=" . urlencode($this->data["days"]['location']);
          $searchDate   = SITE_URL . "/search/tag=" . $this->data["days"]['month'] . '/' . $this->data["days"]['year'];
          ?>
          <span><a href="<?php echo $searchAuthor ?>"><?php echo $this->data["days"]['username']; ?></a></span>
          <?php if ($this->data["days"]['location'] != '') {
            echo(' - <a href="' . $searchLoc . '">' . $this->data["days"]['location'] . '</a>');
          } ?>
        </div>
        <div class="date post">
          <i class="icon"></i>
          <span><a href="<?php echo $searchDate ?>"><?php echo $this->data["time"]; ?></a></span>
        </div>
        <div class="stat post">
        <span class="view">
          <a href="javascript:void(0)" class="comment-count" id="count-<?php echo $this->data["id"]; ?>">
            <i class="icon"></i><?php echo number_format($this->data["days"]['count']); ?>
            </a>
        </span>
        </div>
      </div>
      <hr/>
      <div class="body">
        <p>
          <?php echo($this->data["days"]['content']); ?>
        </p>
      </div>
      <?php
      if ($this->data["photos"] != "") {
        $photos = explode(',', $this->data["photos"]);
        if (sizeof($photos) >= 1) {
          ?>
          <div class="col-md-12 slide">
            <div id="slider" class="flexslider">
              <ul class="slides list-unstyled">
                <?php
                echo(Com::getPhotoSlideFull($photos));
                ?>
              </ul>
            </div>
            <?php if (sizeof($photos) >= 2) { ?>
              <div id="carousel" class="flexslider">
                <ul class="slides list-inline">
                  <?php
                  echo(Com::getPhotoSlideThumb($photos));
                  ?>
                </ul>
              </div>
            <?php } ?>
          </div>
        <?php }
      } ?>
      <div class="action-content" id="action-container">
        <div class="stat">
          <?php if (in_array($this->data["ipaddress"], $this->data["likeIP"])) { ?>
            <span class="like liked" title="Liked">
                  <i class="icon"></i><?php echo number_format($this->data["days"]['like']); ?>
                </span>
          <?php }
          else { ?>
            <span class="like" title="Like">
                  <a href="javascript:void(0)" class="like-day" data-id="<?php echo $id; ?>" data-like="<?php echo $this->data["like"]; ?>">
                    <i class="icon"></i><?php echo number_format($this->data["days"]['like']); ?>
                  </a>
                </span>
          <?php } ?>
          <span class="reply">
                <a href="javascript:void(0)" class="reply-focus">
                  <i class="icon"></i>Reply
                  </a>
                </span>
        </div>
        <div class="social-content">
          <div class="fb-like" data-href="<?php echo $this->data["uri"]; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
        </div>
      </div>
      <div class="comment" id="comment-container">
        <?php
        echo($this->data["comments"]);
        ?>
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
    <?php }
    else { ?>
      <h4>Day not found</h4>
    <?php } ?>
  </div>
  <div class="col-xs-12 col-md-4">
    <?php include_once('include/_right.inc.php'); ?>
  </div>
</div>
<script type="text/javascript">
  $(window).load(function () {
    $('#carousel').flexslider({
      animation:     "slide",
      controlNav:    false,
      animationLoop: false,
      slideshow:     false,
      itemWidth:     75,
      itemMargin:    5,
      asNavFor:      '#slider'
    });
    $('#slider').flexslider({
      animation:     "slide",
      smoothHeight:  true,
      controlNav:    false,
      animationLoop: false,
      slideshow:     false,
      sync:          "#carousel",
      start:         function (slider) {
        $('body').removeClass('loading');
      }
    });
  });

  $(document).ready(function () {
    $('.comment-text').autosize();
    $(".comment-count").live("click", function () {
      scrollTo("#carousel");
    });
  });
</script>