<?php

require_once dirname(__FILE__, 2) . "/version.def.php";
require_once __DIR__ . "/phpsteam.const.php";
require_once __DIR__ . "/steam_attributes.def.php";
require_once __DIR__ . "/steam_types.def.php";

defined("STEAM_SOCKET_TIMEOUT_DEFAULT") or define("STEAM_SOCKET_TIMEOUT_DEFAULT", 60);

defined("LOW_API_CACHE") or define("LOW_API_CACHE", true);
defined("API_DEBUG") or define("API_DEBUG", false);

defined("API_DOUBLE_FILENAME_NOT_ALLOWED") or define("API_DOUBLE_FILENAME_NOT_ALLOWED", true);
defined("API_DOUBLE_FILENAME_RENAME") or define("API_DOUBLE_FILENAME_RENAME", false);
defined("API_MAX_INVENTORY_COUNT") or define("API_MAX_INVENTORY_COUNT", 500);
defined("API_MAX_CONTENT_SIZE") or define("API_MAX_CONTENT_SIZE", 52428800); //50mb
defined("API_ATTRIBUTE_SIZE") or define("API_ATTRIBUTE_SIZE", 0); //TODO
defined("API_MAX_PATH_LENGTH") or define("API_MAX_PATH_LENGTH", 0); //TODO
defined("API_TEMP_DIR") or define("API_TEMP_DIR", sys_get_temp_dir() . "/");
defined("API_VIRUS_SCAN") or define("API_VIRUS_SCAN", false);
defined("DEFAULT_VIRUS_SCAN") or define("DEFAULT_VIRUS_SCAN", "ClamAvScanner");
defined("CLAMAV_BIN") or define("CLAMAV_BIN", "/usr/local/bin/clamscan");
defined("API_GET_CONTENT_READ_DOCUMENTS") or define("API_GET_CONTENT_READ_DOCUMENTS", false);

// config default content provider
defined("DEFAULT_CONTENT_PROVIDER") or define("DEFAULT_CONTENT_PROVIDER", CONTENT_PROVIDER_COAL);

//db connection data
defined("STEAM_DATABASE") or define("STEAM_DATABASE", "steam");
defined("STEAM_DATABASE_HOST") or define("STEAM_DATABASE_HOST", "localhost");
defined("STEAM_DATABASE_USER") or define("STEAM_DATABASE_USER", "steam");
defined("STEAM_DATABASE_PASS") or define("STEAM_DATABASE_PASS", "steam");

// config persistence
defined("ENABLE_FILE_PERSISTENCE") or define("ENABLE_FILE_PERSISTENCE", false);
defined("FILE_PERSISTENCE_BASE_PATH") or define("FILE_PERSISTENCE_BASE_PATH", false);
defined("DEFAULT_PERSISTENCE_TYPE") or define("DEFAULT_PERSISTENCE_TYPE", PERSISTENCE_DATABASE);

defined("MIMETYPE_STORAGE_PATH") or define("MIMETYPE_STORAGE_PATH", API_TEMP_DIR);
defined("THUMBNAIL_PATH") or define("THUMBNAIL_PATH", API_TEMP_DIR);

defined("DOWNLOAD_RANGE_SPEEDLIMIT") or define("DOWNLOAD_RANGE_SPEEDLIMIT", 200); //200 kb
