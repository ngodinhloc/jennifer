<div id="comment-<?= $this->data["comment"]["id"] ?>" class="media comment <?= $this->data["comment"]["repClass"] ?>">
  <div class="media-body">
    <div class="author">
      <i class="icon"></i><span id="name-<?= $this->data["comment"]["id"] ?>"><?= $this->data["comment"]["username"] ?></span>
    </div>
    <div class="date"><i class="icon"></i><span><?= $this->data["comment"]["time"] ?></span></div>
    <p><?= $this->data["comment"]["repName"] ?><?= $this->data["comment"]["content"] ?></p>
    <div class="stat pull-right">
      <? if ($this->data["comment"]["liked"]) { ?>
        <span class="like liked" title="Liked"><i class="icon"></i><?= $this->data["comment"]["like"] ?></span>
      <? }
      else { ?>
        <span class="like" title="Like">
        <a id="like-com-<?= $this->data["comment"]["id"] ?>-<?= $this->data["comment"]["like"] ?>" class="like-com" href="javascript:void(0)" data-id="<?= $this->data["comment"]["id"] ?>" data-like="<?= $this->data["comment"]["like"] ?>"><i class="icon"></i>3</a>
      </span>
      <? } ?>
      <? if ($this->data["comment"]["disliked"]) { ?>
        <span class="underlike disliked" title="Disliked"><i class="icon"></i><?= $this->data["comment"]["dislike"] ?></span>
      <? }
      else { ?>
        <span class="underlike" title="Dislike">
        <a class="dislike-com" href="javascript:void(0)" data-id="<?= $this->data["comment"]["id"] ?>" data-dislike="1"><i class="icon"></i><?= $this->data["comment"]["dislike"] ?></a>
      </span>
      <? } ?>
      <span class="reply">
        <a id="<?= $this->data["comment"]["repID"] ?>-<?= $this->data["comment"]["id"] ?>" class="reply-display" href="javascript:void(0)" data-com-id="<?= $this->data["comment"]["id"] ?>" data-rep-id="<?= $this->data["comment"]["repID"] ?>"><i class="icon"></i>Reply</a>
      </span>
    </div>
  </div>
</div>