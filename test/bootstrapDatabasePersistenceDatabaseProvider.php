<?php
ini_set("display_errors", 1);
error_reporting(E_ALL | E_NOTICE);
require dirname(__FILE__) . '/etc/default.def.php';
include_once 'statistic.php';

define("ENABLE_FILE_PERSISTENCE", false);
defined("DEFAULT_CONTENT_PROVIDER") or define("DEFAULT_CONTENT_PROVIDER", 0x00000002);

require dirname(__FILE__) . '/deps/vendor/autoload.php';