<?php

include_once dirname(dirname(__FILE__)) . "/version.def.php";
include_once dirname(__FILE__) . "/phpsteam.const.php";
include_once dirname(__FILE__) . "/steam_attributes.def.php";
include_once dirname(__FILE__) . "/steam_types.def.php";

defined("STEAM_SOCKET_TIMEOUT_DEFAULT") or define("STEAM_SOCKET_TIMEOUT_DEFAULT", 60);

defined("LOW_API_CACHE") or define("LOW_API_CACHE", true);
defined("API_DEBUG") or define("API_DEBUG", false);

defined("API_DOUBLE_FILENAME_NOT_ALLOWED") or define("API_DOUBLE_FILENAME_NOT_ALLOWED", true);
defined("API_MAX_INVENTORY_COUNT") or define("API_MAX_INVENTORY_COUNT", 500);
defined("API_MAX_CONTENT_SIZE") or define("API_MAX_CONTENT_SIZE", 52428800); //50mb
defined("API_ATTRIBUTE_SIZE") or define("API_ATTRIBUTE_SIZE", 0); //TODO
defined("API_MAX_PATH_LENGTH") or define("API_MAX_PATH_LENGTH", 0); //TODO
defined("API_TEMP_DIR") or define("API_TEMP_DIR", sys_get_temp_dir() . "/");
defined("API_VIRUS_SCAN") or define("API_VIRUS_SCAN", false);
defined("DEFAULT_VIRUS_SCAN") or define("DEFAULT_VIRUS_SCAN", "ClamAvScanner");
defined("CLAMAV_BIN") or define("CLAMAV_BIN", "/usr/local/bin/clamscan");


// config default content provider
defined("DEFAULT_CONTENT_PROVIDER") or define("DEFAULT_CONTENT_PROVIDER", CONTENT_PROVIDER_COAL);

// config persistence
defined("ENABLE_FILE_PERSISTENCE") or define("ENABLE_FILE_PERSISTENCE", false);
defined("FILE_PERSISTENCE_BASE_PATH") or define("FILE_PERSISTENCE_BASE_PATH", false);
defined("DEFAULT_PERSISTENCE_TYPE") or define("DEFAULT_PERSISTENCE_TYPE", PERSISTENCE_DATABASE);

defined("MIMETYPE_STORAGE_PATH") or define("MIMETYPE_STORAGE_PATH", API_TEMP_DIR);
defined("THUMBNAIL_PATH") or define("THUMBNAIL_PATH", API_TEMP_DIR);