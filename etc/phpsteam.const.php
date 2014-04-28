<?php
// define download types
define("DOWNLOAD_ATTACHMENT", "attachment");
define("DOWNLOAD_IMAGE", "image");
define("DOWNLOAD_INLINE", "inline");
define("DOWNLOAD_RANGE", "range");

// define content provider types
define("CONTENT_PROVIDER_COAL",     0x00000000);
define("CONTENT_PROVIDER_STEAMWEB", 0x00000001);
define("CONTENT_PROVIDER_DATABASE", 0x00000002);

// define persistence types
define("PERSISTENCE_DATABASE",       0x00000000);
define("PERSISTENCE_FILE",           0x00001000);
define("PERSISTENCE_FILE_UID",       0x00000002 | PERSISTENCE_FILE); // 4098
define("PERSISTENCE_FILE_HASH",      0x00000003 | PERSISTENCE_FILE); // 4099
define("PERSISTENCE_FILE_CONTENTID", 0x00000004 | PERSISTENCE_FILE); // 4100

// exception types
define("E_ERROR",          1<<0 ); // an error has occured
define("E_LOCAL",          1<<1 ); // local exception, user defined
define("E_MEMORY",         1<<2 ); // some memory messed up, uninitialized mapping,etc
define("E_EVENT",          1<<3 ); // some exception on an event
define("E_ACCESS",         1<<4 );
define("E_PASSWORD",       1<<5 );
define("E_NOTEXIST",       1<<6 );
define("E_FUNCTION",       1<<7 );
define("E_FORMAT",         1<<8 );
define("E_OBJECT",         1<<9 );
define("E_TYPE",           1<<10);
define("E_MOVE",           1<<11);
define("E_LOOP",           1<<12);
define("E_LOCK",           1<<13);
define("E_QUOTA",          1<<14);
define("E_TIMEOUT",        1<<15);
define("E_CONNECT",        1<<16);
define("E_UPLOAD",         1<<17);
define("E_DOWNLOAD",       1<<18);
define("E_DELETED",        1<<19);
define("E_ERROR_PROTOCOL", 1<<20);

define("API_LOGGER_CHANNEL", "phpsteam");

include_once dirname(__FILE__) . "/logger.conf.php";
