<div class="panel panel-default what-hot">
  <div class="panel-heading">
    <h3 class="panel-title">Related Days</h3>
  </div>
  <div class="panel-body">
    <ul class="list-unstyled">
      <?php
      if ($this->data["relatedDays"] != "") {
        echo($this->data["relatedDays"]);
      }
      else {
        echo("No related days found");
      }
      ?>
    </ul>
  </div>
</div>
<div class="panel panel-default related-posts">
  <div class="panel-heading">
    <h3 class="panel-title">Most Liked Days</h3>
  </div>
  <div class="panel-body">
    <ul class="list-unstyled">
      <?php echo($this->data["topDays"]); ?>
    </ul>
  </div>

</div>
<div class="panel panel-default facebook">
  <div class="fb-page" data-href="https://www.facebook.com/thedaysoflife" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
    <div class="fb-xfbml-parse-ignore">
      <blockquote cite="https://www.facebook.com/thedaysoflife"><a href="https://www.facebook.com/thedaysoflife">The
          Days Of Life</a></blockquote>
    </div>
  </div>
</div>