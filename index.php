<?php
// ini_set("display_errors", 1);

require_once 'utils/debug.php';
require_once 'utils/format.php';
require_once 'utils/Debugger.php';
require_once 'utils/helper.php';
require_once 'debbug/error.php';
require_once 'Boot.php';

define('__ROOT__', str_replace('\\', '/', __DIR__));
$boot = Boot::get_instance();
$boot->call($_SERVER['REQUEST_URI']);

?>