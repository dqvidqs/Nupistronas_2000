<?php
ini_set("display_errors", 0);


define('__ROOT__', str_replace('\\', '/', __DIR__));
require_once __ROOT__ . '/autoload.php';

set_exception_handler('exception_handler');
// restore_error_handler ('error_handler');

$boot = Bootstrap::get_instance();
$boot->call($_SERVER['REQUEST_URI']);

?>