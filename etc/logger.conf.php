<?php
use Monolog\Logger;
use Monolog\Registry;
use Monolog\Handler\ErrorLogHandler;

$loader = include __DIR__ . "/../../../autoload.php";
$loader->add('Monolog\\', __DIR__);

$logger = new Logger(API_LOGGER_CHANNEL);
$logger->pushHandler(new ErrorLogHandler());

Registry::addLogger($logger);

$logger->addDebug("API Logger init finished");