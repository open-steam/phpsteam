<?php

class steam_document_test extends UnitTestCase {
	
    function setUp() {
        $GLOBALS["STEAM"] = steam_connector::connect(STEAM_SERVER, STEAM_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
        $this->assertTrue($GLOBALS["STEAM"]->get_login_status());
    }
    
    function tearDown() {
        $GLOBALS["STEAM"]->disconnect();
    }
	
	function test_set_content() {
	}
	
}
?>
