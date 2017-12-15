<?php

  use jennifer\fb\FacebookHelper;
  use thedaysoflife\com\Com;

?>
  <table>
    <thead>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Author</th>
      <th>Com</th>
      <th>Like</th>
      <th colspan='2'>To Facebook</th>
      <th colspan='2'>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($this->data["days"] as $day) {
      $link    = Com::getDayLink($day);
      $options = FacebookHelper::getActOptions($day["fb"]);
      ?>
      <tr id="row-<?= $day["id"] ?>">
        <td><?= $day["id"] ?></td>
        <td>
          <a target="_blank" href="<?= $link ?>">
            <?= $day['day'] ?>/<?= $day['month'] ?>/<?= $day['year'] ?>: <?= stripslashes($day['title']) ?>
          </a>
        </td>
        <td><?= stripslashes($day['username']) ?></td>
        <td><?= number_format($day['count']) ?></td>
        <td><? number_format($day['like']) ?></td>
        <td>
          <select class="fb-type <?php if ($day["fb"]) {
            echo "fb-posted";
          } ?>" id="fb-type-<?= $day['id'] ?>">
            <?= $options ?>
          </select>
        </td>
        <td id="fb-post-<?= $day['id'] ?>">
          <a title="Post to Facebook" href="javascript:void(0)" class="fb-post-button" data-id="<?= $day['id'] ?>">
            <span class="glyphicon glyphicon-send"></span>
          </a>
        </td>
        <td><a title="Edit" href="/back/day/<?= $day['id'] ?>/"><span class="glyphicon glyphicon-edit"></span></a></td>
        <td>
          <a title="Remove" href="javascript:void(0)" class="remove-day" id="remove-day-<?= $day['id'] ?>" data-day-id="<?= $day['id'] ?>">
            <span class="glyphicon glyphicon-remove"></span>
          </a>
        </td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
<?= $this->data["pagination"] ?>