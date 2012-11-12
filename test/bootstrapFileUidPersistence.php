<?php
ini_set("display_errors", 1);
error_reporting(E_ALL | E_NOTICE);
require dirname(__FILE__) . '/etc/default.def.php';
include_once 'statistic.php';

define("ENABLE_FILE_PERSISTENCE", true);
define("DEFAULT_PERSISTENCE_TYPE", 0x00000002 | 0x00000001);

require dirname(__FILE__) . '/deps/vendor/autoload.php';