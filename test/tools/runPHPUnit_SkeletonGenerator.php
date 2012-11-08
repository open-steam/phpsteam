#!/usr/bin/php
<?php

$pathSkeletonGenerator = __DIR__ . "/";

echo "** Run SkeletonGenerator **" . PHP_EOL;

if (!file_exists($pathSkeletonGenerator . "phpunit-skelgen.phar")) {
	passthru("cd $pathSkeletonGenerator; wget http://pear.phpunit.de/get/phpunit-skelgen.phar; chmod +x phpunit-skelgen.phar");
}

$classes = array(
//				"steam_calendar" => "steam_calendar",
//				"steam_container" => "steam_container",
//				"steam_database" => "steam_database",
//				"steam_date" => "steam_date",
//				"steam_docextern" => "steam_docextern",
//				"steam_document" => "steam_document",
//				"steam_exit" => "steam_exit",
//				"steam_function" => "steam_function",
//				"steam_group" => "steam_group",
//				"steam_link" => "steam_link",
//				"steam_messageboard" => "steam_messageboard",
//				"steam_object" => "steam_object",
//				"steam_room" => "steam_room",
//				"steam_script" => "steam_script",
//				"steam_trashbin" => "steam_trashbin",
//				"steam_user" => "steam_user",
//				"steam_wiki" => "steam_wiki",
//				"steam_connection" => "Connection/steam_connection",
//				"steam_connector" => "Connection/steam_connector",
//				"steam_connector_lite" => "Connection/steam_connector_lite",
//				"steam_factory" => "Connection/steam_factory",
//				"steam_request" => "Connection/steam_request",
//				"ParameterException" => "Exceptions/ParameterException",
//				"steam_exception" => "Exceptions/steam_exception",
//				"MimetypeHelper" => "Helper/MimetypeHelper",
//				"ThumbnailHelper" => "Helper/ThumbnailHelper",
				"OpenSteam\\Persistence\\DatabasePersistence", "Persistence/DatabasePersistence",
);

foreach ($classes as $class => $filePath) {
	passthru("./phpunit-skelgen.phar --bootstrap bootstrap.php --test -- {$class} ../../classes/{$filePath}.class.php {$class}_Test ../tests/{$filePath}_Test.php");
}