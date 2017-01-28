<?php
use core\View;

define('PAGE_TITLE', 'About');
$view = new View();
$tag  = 'about';
$info = $view->getInfoByTag($tag);
?>
<?php print(stripslashes($info['content'])); ?>