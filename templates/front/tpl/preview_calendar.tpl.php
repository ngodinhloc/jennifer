<? use thedaysoflife\com\Com; ?>
<? foreach ($this->data["years"] as $year) {
  $link = Com::getSearchLink($year, false); ?>
  <div class="calendar-div"><h4><a href="<?= $link ?>"><?= $year ?></a></h4>
    <? foreach ($this->data["months"] as $abr => $month) {
      $dayNum = $this->data["days"][$year][$month];
      if (sizeof($dayNum) > 0) {
        $tag  = $month . '/' . $year;
        $link = Com::getSearchLink($tag, false); ?>
        <ul class="list-unstyled calendar-year">
          <li class="calendar">
            <a href="<?= $link ?>" title="<?= $tag ?>: <?= number_format(sizeof($dayNum)) ?> shares"><b><?= $abr ?></b></a>
          </li>
          <? foreach ($dayNum as $day => $num) {
            $tag  = ($day > 0) ? ($day . '/' . $month . '/' . $year) : ($month . '/' . $year);
            $link = Com::getSearchLink($tag, false); ?>
            <li class="calendar">
              <a href="<?= $link ?>" title="<?= $tag ?>: <?= number_format(sizeof($num)) ?> shares"><?= $day ?></a>
            </li>
          <? } ?>
        </ul>
        <br>
      <? } ?>
    <? } ?>
  </div>
<? } ?>