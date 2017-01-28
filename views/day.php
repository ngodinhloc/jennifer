<?php
use sys\System;
use com\Com;
use core\View;

$id = System::getDayPara();
if ($id > 0) {
  $view = new View();
  $days = $view->getDayById($id);
  if (isset($days['id'])) {
    $like      = (int)$days['like'];
    $likeIP    = explode('|', $days['like_ip']);
    $time      = Com::getTimeDiff($days['time']);
    $ipaddress = System::getTodayIPaddress();
    $day       = (int)$days['day'];
    $month     = (int)$days['month'];
    $year      = (int)$days['year'];
    $location  = $days['location'];
    $uri       =
      LIST_URL . $days['id'] . '/' . $days['day'] . $days['month'] . $days['year'] . '-' . $days['slug'] . URL_EXT;
    $photos    = trim($days['photos']);
    if ($photos != "") {
      $imgs   = explode(',', $photos);
      $img    = $imgs[0];
      $imgURL = Com::getPhotoURL($img, PHOTO_FULL_NAME);
      //$fist_img	= '<a href="'.$link.'"><img src="'.$img_url.'"/></a>';
    }
    $pageTitle = $days['day'] . '/' . $days['month'] . '/' . $days['year'] . ': ' . $days['title'];
    $pageDesc  = strip_tags(Com::getDescription($days['content']));
    $pageKeys  = $days['title'];
    define("PAGE_TITLE", $pageTitle);
  }
}
?>
<? include_once('include/_facebook.php'); ?>
<div class="row">
  <div class="col-md-12 col-md-8 no-front">
    <?php if (isset($days['id'])) { ?>
      <h4 class="title">
        <?php echo $days['day'] . '/' . $days['month'] . '/' . $days['year'] . ': ' . $days['title']; ?>
        <input type="hidden" id="day-id" value="<?php echo $id; ?>">
      </h4>
      <div class="post-meta">
        <div class="author post">
          <i class="icon"></i>
          <?php
          $searchAuthor = SITE_URL . "/search/tag=" . urlencode($days['username']);
          $searchLoc    = SITE_URL . "/search/tag=" . urlencode($days['location']);
          $searchDate   = SITE_URL . "/search/tag=" . $days['month'] . '/' . $days['year'];
          ?>
          <span><a href="<?php echo $searchAuthor ?>"><?php echo $days['username']; ?></a></span>
          <?php if ($days['location'] != '') {
            echo(' - <a href="' . $searchLoc . '">' . $days['location'] . '</a>');
          } ?>
        </div>
        <div class="date post">
          <i class="icon"></i>
          <span><a href="<?php echo $searchDate ?>"><?php echo $time; ?></a></span>
        </div>
        <div class="stat post">
        <span class="view">
          <a href="javascript:void(0)" class="comment-count" id="count-<?php echo $id; ?>">
            <i class="icon"></i><?php echo number_format($days['count']); ?>
            </a>
        </span>
        </div>
      </div>
      <hr/>
      <div class="body">
        <p>
          <?php echo($days['content']); ?>
        </p>
      </div>
      <?php
      if ($photos != "") {
        $photos = explode(',', $photos);
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
          <?php if (in_array($ipaddress, $likeIP)) { ?>
            <span class="like liked" title="Liked">
                  <i class="icon"></i><?php echo number_format($days['like']); ?>
                </span>
          <?php }
          else { ?>
            <span class="like" title="Like">
                  <a href="javascript:void(0)" class="like-day" data-id="<?php echo $id; ?>" data-like="<?php echo $like; ?>">
                    <i class="icon"></i><?php echo number_format($days['like']); ?>
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
          <div class="fb-like" data-href="<?php echo $uri; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
        </div>
      </div>
      <div class="comment" id="comment-container">
        <?php
        echo($view->getComments($id));
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
    <?php include_once('include/_right.php'); ?>
  </div>
</div>
<meta property="fb:admins" content="<?php echo FB_PAGEID; ?>"/>
<meta property="og:image" content="<?php echo $imgURL; ?>"/>
<meta property="og:title" content="<?php echo $pageTitle; ?>"/>
<meta property="og:description" content="<?php echo $pageDesc; ?>"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="<?php echo $uri; ?>"/>
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