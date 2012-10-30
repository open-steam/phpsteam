<?php

class steam_factory_test extends UnitTestCase {
	
    function setUp() {
        $GLOBALS["STEAM"] = steam_connector::connect(STEAM_SERVER, STEAM_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
        $this->assertTrue($GLOBALS["STEAM"]->get_login_status());
    }
    
    function tearDown() {
        $GLOBALS["STEAM"]->disconnect();
    }
	
	function test_groupname_to_object() {
		$steam_group = steam_factory::groupname_to_object($GLOBALS["STEAM"]->get_id(), "steam");
		$this->assertTrue(is_object($steam_group));
		$this->assertTrue($steam_group instanceof steam_group);
		$this->assertTrue($steam_group->get_name() === "sTeam");	
	}
	
}
?>