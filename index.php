<?php
ini_set("display_errors", 1);

define('__ROOT__', str_replace('\\', '/', __DIR__));
require_once __ROOT__ . '/autoload.php';

$boot = Bootstrap::get_instance();
$boot->call($_SERVER['REQUEST_URI']);

?>