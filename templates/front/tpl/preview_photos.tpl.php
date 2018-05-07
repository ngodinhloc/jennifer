<? foreach ($this->data["photos"] as $photo) { ?>
  <li>
    <a href="<?= $photo["link"] ?>" title="<?= htmlspecialchars($photo["title"]) ?>">
      <img class="photo-thumb" src="<?= $photo["photoURL"] ?>">
    </a>
  </li>
<? } ?>