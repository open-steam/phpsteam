#!/usr/bin/php
<?php

$pathSkeletonGenerator = __DIR__ . "/";

echo "** Run SkeletonGenerator **" . PHP_EOL;

if (!file_exists($pathSkeletonGenerator . "phpunit-skelgen.phar")) {
	passthru("cd $pathSkeletonGenerator; wget http://pear.phpunit.de/get/phpunit-skelgen.phar; chmod +x phpunit-skelgen.phar");
}

$classes = array("steam_calendar",
				 "steam_container",
				 "steam_database",
				 "steam_date",
				 "steam_docextern",
				 "steam_document",
				 "steam_exit",
				 "steam_function",
				 "steam_group",
				 "steam_link",
				 "steam_messageboard",
				 "steam_object",
				 "steam_room",
				 "steam_script",
				 "steam_trashbin",
				 "steam_user",
				 "steam_wiki");

foreach ($classes as $class) {
	passthru("./phpunit-skelgen.phar --bootstrap bootstrap.php --test -- {$class} ../../classes/{$class}.class.php {$class}_Test ../tests/{$class}_Test.php");
}