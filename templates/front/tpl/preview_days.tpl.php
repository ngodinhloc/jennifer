<? foreach ($this->data["days"] as $day) { ?>
  <li class="item">
    <div class="images">
      <a href="<?= $day["link"] ?>"><img src="<?= $day["photoURL"] ?>"></a>
    </div>
    <div class="body">
      <p><a href="<?= $day["link"] ?>"><?= htmlspecialchars($day["title"]) ?></a></p>
      <p><?= htmlspecialchars($day["preview"]) ?><a href="<?= $day["link"] ?>">...more »</a></p>
      <p class="author-location"><?= $day["meta"] ?></p>
      <p class="author-location">
        <a href="<?= $day["authorLink"] ?>"><?= htmlspecialchars($day["author"], ENT_QUOTES) ?></a>
        <? if ($day["locationLink"]){ ?>
        - <a href="<?= htmlspecialchars($day["locationLink"]) ?>"><i><?= htmlspecialchars($day["location"]) ?></i></a>
      </p>
      <? } ?>
    </div>
    <div class="stat">
      <span class="view"><a href="<?= $day["link"] ?>#action-container"><i class="icon"></i><?= $day["count"] ?></a></span>
      <? if ($day["liked"]) { ?>
        <span class="like liked" title="Liked"><i class="icon"></i><?= $day["like"] ?></span>
      <? }
      else { ?>
        <span class="like" title="Like"><a class="like-day" href="javascript:void(0)" data-id="<?= $day["id"] ?>" data-like="<?= $day["like"] ?>"><i class="icon"></i><?= $day["like"] ?></a></span>
      <? } ?>
      <?= $day["state"] ?>
    </div>
  </li>
<? } ?>