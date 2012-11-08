<?php

require dirname(dirname(__FILE__)) . '/etc/default.def.php';
require dirname(dirname(__FILE__)) . '/deps/vendor/autoload.php';

class steam_document_Test extends PHPUnit_Framework_TestCase {
	
    function setUp() {
        $GLOBALS["STEAM"] = steam_connector::connect(STEAM_SERVER, STEAM_SERVER_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
        $this->assertTrue($GLOBALS["STEAM"]->get_login_status());
    }
    
    function tearDown() {
        $GLOBALS["STEAM"]->disconnect();
    }
	
	function test_set_content() {
	}
	
}
?>
