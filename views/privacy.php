<?php
use core\View;

define('PAGE_TITLE', 'Privacy');
$view = new View();
$tag  = 'privacy';
$info = $view->getInfoByTag($tag);
?>
<?php echo stripslashes($info['content']); ?>
