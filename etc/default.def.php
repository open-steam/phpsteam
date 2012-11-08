<?php

require dirname(dirname(__FILE__)) . "/version.def.php";

defined("STEAM_SOCKET_TIMEOUT_DEFAULT") or define("STEAM_SOCKET_TIMEOUT_DEFAULT", 60);

// define persistence types
define("PERSISTENCE_DATABASE","Database");
define("PERSISTENCE_FILERANDOM", "FileRandom");

// define download types
define("DOWNLOAD_ATTACHMENT", "attachment");
define("DOWNLOAD_IMAGE", "image");
define("DOWNLOAD_INLINE", "inline");
define("DOWNLOAD_RANGE", "range");

// define content provider types
define("CONTENT_PROVIDER_COAL", "coal");
define("CONTENT_PROVIDER_DATABASE", "database");
define("CONTENT_PROVIDER_STEAMWEB", "steamweb");

defined("CONF_CONTENT_PROVIDER") or define("CONF_CONTENT_PROVIDER", CONTENT_PROVIDER_STEAMWEB);