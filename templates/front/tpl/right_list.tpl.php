<?php foreach ($this->data["days"] as $day) { ?>
  <li class="right-list">
    <div class="right-thumb">
      <a href="<?= $day["link"] ?>">
        <img src="<?= $day["photoURL"] ?>">
      </a>
    </div>
    <div class="right-title">
      <a href="<?= $day["link"] ?>"><?= $day["title"] ?></a></div>
    <div class="clear-both"></div>
  </li>
<? } ?>