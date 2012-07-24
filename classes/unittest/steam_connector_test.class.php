<?php

class steam_connector_test extends UnitTestCase {
	
	function test_get_login_status() {
		$steam_connector = steam_connector::connect(STEAM_SERVER, STEAM_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
		$this->assertTrue($steam_connector->get_login_status(), "checking get_login_status on success");
		
		$steam_connector = steam_connector::connect(STEAM_SERVER, STEAM_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
		$this->assertFalse($steam_connector->get_login_status(), "checking get_login_status on fail");
	}
	
}
?>