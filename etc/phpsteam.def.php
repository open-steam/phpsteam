<?php

include_once dirname(dirname(__FILE__)) . "/version.def.php";
include_once dirname(__FILE__) . "/steam_attributes.def.php";
include_once dirname(__FILE__) . "/steam_types.def.php";

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
define("CONTENT_PROVIDER_COAL", "OpenSteam\\Persistence\\ContentProvider\\CoalContentProvider");
define("CONTENT_PROVIDER_DATABASE", "OpenSteam\\Persistence\\ContentProvider\\DatabaseContentProvider");
define("CONTENT_PROVIDER_STEAMWEB", "OpenSteam\\Persistence\\ContentProvider\\SteamWebContentProvider");
defined("DEFAULT_CONTENT_PROVIDER") or define("DEFAULT_CONTENT_PROVIDER", CONTENT_PROVIDER_COAL);

defined("LOW_API_CACHE") or define("LOW_API_CACHE", true);
defined("API_DEBUG") or define("API_DEBUG", false);

defined("ENABLE_FILESYTEM_PERSISTENCE") or define("ENABLE_FILESYTEM_PERSISTENCE", false);

//sub-directories: uuid, hash
defined("FILESYTEM_PERSISTENCE_BASE_PATH") or define("FILESYTEM_PERSISTENCE_BASE_PATH", false);
defined("DEFAULT_FILESYTEM_PERSISTENCE_TYPE") or define("DEFAULT_FILESYTEM_PERSISTENCE_TYPE", "RANDOM");