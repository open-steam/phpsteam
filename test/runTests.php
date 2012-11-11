#!/usr/bin/php
<?php

$pathComposer = __DIR__ . "/composer/";

echo "** Run composer **" . PHP_EOL;

$returnStatus = false;
exec('curl 2> /dev/null', $output, $returnStatus);
if ($returnStatus == 127) {
	die("Please install curl (e.g. apt-get install curl or brew install curl");
}
if (file_exists($pathComposer . "composer.phar")) {
	passthru("cd $pathComposer; php composer.phar self-update");
} else {
	passthru("cd $pathComposer; curl -s https://getcomposer.org/installer | php");
}

passthru("cd $pathComposer; php composer.phar update");

passthru("deps/vendor/bin/phpunit --bootstrap bootstrapDatabasePersistenceCoalProvider.php tests-function");

passthru("deps/vendor/bin/phpunit --bootstrap bootstrapDatabasePersistenceDatabaseProvider.php tests-function");

passthru("deps/vendor/bin/phpunit --bootstrap bootstrapDatabasePersistenceSteamWebProvider.php tests-function");

passthru("deps/vendor/bin/phpunit --bootstrap bootstrapFileUidPersistence.php tests-function");