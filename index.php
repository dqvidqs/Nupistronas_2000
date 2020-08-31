<?php
require_once 'utils/debbug.php';
require_once 'utils/helper.php';
require_once 'Boot.php';

$boot = Boot::get_instance();
$boot->call($_SERVER['REQUEST_URI']);

?>