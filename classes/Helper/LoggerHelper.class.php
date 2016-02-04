<?php
namespace OpenSteam\Helper;

use Monolog\Logger;
use Monolog\Registry;

class LoggerHelper {

	private static $_instance;
	private $logger;
	private $loggerWrapper;

	private function __construct() {
		$this->loggerWrapper = new LoggerWrapper();
	}

	public static function getInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function createLogger() {
		$logger = new Logger(API_LOGGER_CHANNEL);
		Registry::addLogger($logger);
		$this->setLogger($logger);
	}

	public function setLogger($logger) {
		$this->logger = $logger;
	}

	public function getLogger() {
		return $this->loggerWrapper;
	}

	public function getLoggerObject() {
		return $this->logger;
	}

}

class LoggerWrapper {

	public function __call($name, array $arguments) {
		$logger = LoggerHelper::getInstance()->getLoggerObject();

		if (isset($logger) && API_DEBUG) {
			return call_user_func_array(array($logger, $name), $arguments);
		}
	}
}