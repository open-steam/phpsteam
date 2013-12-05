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
define("PERSISTENCE_FILE_UID",       0x00000002 | PERSISTENCE_FILE);
define("PERSISTENCE_FILE_HASH",      0x00000003 | PERSISTENCE_FILE);
define("PERSISTENCE_FILE_CONTENTID", 0x00000004 | PERSISTENCE_FILE);