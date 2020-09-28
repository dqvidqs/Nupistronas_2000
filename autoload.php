<?php

define('__PHP__', __ROOT__ . '/php');
define('__UTILS__', __ROOT__ . '/php/utils');

require_once __UTILS__ . '/debug.php';
require_once __UTILS__ . '/error.php';
require_once __UTILS__ . '/format.php';
require_once __UTILS__ . '/helper.php';

define('__CORE__', __ROOT__ . '/core');

require_once __CORE__ . '/Bootstrap.php';
require_once __CORE__ . '/Controller.php';
require_once __CORE__ . '/View.php';

define('__LAYOUT__', __ROOT__ . '/layout');

define('__MODULES__', __ROOT__ . '/modules');

?>