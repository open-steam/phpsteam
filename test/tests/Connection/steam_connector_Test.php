<?php

require dirname(dirname(dirname(__FILE__))) . '/etc/default.def.php';
require dirname(dirname(dirname(__FILE__))) . '/deps/vendor/autoload.php';

class steam_connector_Test extends PHPUnit_Framework_TestCase {

	function test_connect() {
		$steam_connector = steam_connector::connect(STEAM_SERVER, STEAM_SERVER_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
		$this->assertTrue($steam_connector instanceof steam_connector);
	}

	function test_get_login_status() {
		$steam_connector = steam_connector::connect(STEAM_SERVER, STEAM_SERVER_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
		$this->assertTrue($steam_connector->get_login_status(), "checking get_login_status on success");

		$steam_connector = steam_connector::connect(STEAM_SERVER, STEAM_SERVER_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
		$this->assertFalse($steam_connector->get_login_status(), "checking get_login_status on fail");
	}

}