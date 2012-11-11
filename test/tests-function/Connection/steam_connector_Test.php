<?php

class steam_connector_Test extends PHPUnit_Framework_TestCase
{

    public function testConnect()
    {
		$steam_connector = steam_connector::connect(STEAM_SERVER, STEAM_SERVER_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
		$this->assertTrue($steam_connector instanceof steam_connector);
		$this->assertTrue($steam_connector->is_connected());
		$steam_connector->disconnect();
    }

    /**
     * @covers steam_connector::get_instance
     * @todo   Implement testGet_instance().
     */
    public function testGet_instance()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::serialize
     * @todo   Implement testSerialize().
     */
    public function testSerialize()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::unserialize
     * @todo   Implement testUnserialize().
     */
    public function testUnserialize()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_id
     * @todo   Implement testGet_id().
     */
    public function testGet_id()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    public function testGet_login_status()
    {
		$steam_connector = steam_connector::connect(STEAM_SERVER, STEAM_SERVER_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
		$this->assertTrue($steam_connector->get_login_status(), "checking get_login_status on success");
		$steam_connector->disconnect();

		$steam_connector = steam_connector::connect(STEAM_SERVER, STEAM_SERVER_PORT, "dfds", "sadfs");
		$this->assertFalse($steam_connector->get_login_status(), "checking get_login_status on fail");
		$steam_connector->disconnect();
    }

    /**
     * @covers steam_connector::get_login_data
     * @todo   Implement testGet_login_data().
     */
    public function testGet_login_data()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_current_steam_user
     * @todo   Implement testGet_current_steam_user().
     */
    public function testGet_current_steam_user()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::disconnect
     * @todo   Implement testDisconnect().
     */
    public function testDisconnect()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_server
     * @todo   Implement testGet_server().
     */
    public function testGet_server()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_last_reboot
     * @todo   Implement testGet_last_reboot().
     */
    public function testGet_last_reboot()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_port
     * @todo   Implement testGet_port().
     */
    public function testGet_port()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_root_room
     * @todo   Implement testGet_root_room().
     */
    public function testGet_root_room()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_module
     * @todo   Implement testGet_module().
     */
    public function testGet_module()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_service_manager
     * @todo   Implement testGet_service_manager().
     */
    public function testGet_service_manager()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::is_service
     * @todo   Implement testIs_service().
     */
    public function testIs_service()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::call_service
     * @todo   Implement testCall_service().
     */
    public function testCall_service()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_services
     * @todo   Implement testGet_services().
     */
    public function testGet_services()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_factory
     * @todo   Implement testGet_factory().
     */
    public function testGet_factory()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::list_modules
     * @todo   Implement testList_modules().
     */
    public function testList_modules()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_steam_group
     * @todo   Implement testGet_steam_group().
     */
    public function testGet_steam_group()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_database
     * @todo   Implement testGet_database().
     */
    public function testGet_database()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_pike_version
     * @todo   Implement testGet_pike_version().
     */
    public function testGet_pike_version()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::set_socket_timeout
     * @todo   Implement testSet_socket_timeout().
     */
    public function testSet_socket_timeout()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_server_version
     * @todo   Implement testGet_server_version().
     */
    public function testGet_server_version()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_request_count
     * @todo   Implement testGet_request_count().
     */
    public function testGet_request_count()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_globalrequest_count
     * @todo   Implement testGet_globalrequest_count().
     */
    public function testGet_globalrequest_count()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_globalrequest_map
     * @todo   Implement testGet_globalrequest_map().
     */
    public function testGet_globalrequest_map()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_globalrequest_time
     * @todo   Implement testGet_globalrequest_time().
     */
    public function testGet_globalrequest_time()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::upload
     * @todo   Implement testUpload().
     */
    public function testUpload()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::install_package
     * @todo   Implement testInstall_package().
     */
    public function testInstall_package()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::send_mail_from
     * @todo   Implement testSend_mail_from().
     */
    public function testSend_mail_from()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_config_value
     * @todo   Implement testGet_config_value().
     */
    public function testGet_config_value()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_login_user_name
     * @todo   Implement testGet_login_user_name().
     */
    public function testGet_login_user_name()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::quoted_printable_encode
     * @todo   Implement testQuoted_printable_encode().
     */
    public function testQuoted_printable_encode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::predefined_command
     * @todo   Implement testPredefined_command().
     */
    public function testPredefined_command()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::buffer_flush
     * @todo   Implement testBuffer_flush().
     */
    public function testBuffer_flush()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_socket_status
     * @todo   Implement testGet_socket_status().
     */
    public function testGet_socket_status()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::exception
     * @todo   Implement testException().
     */
    public function testException()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::buffer_attributes_request
     * @todo   Implement testBuffer_attributes_request().
     */
    public function testBuffer_attributes_request()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::get_transaction_id
     * @todo   Implement testGet_transaction_id().
     */
    public function testGet_transaction_id()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::command
     * @todo   Implement testCommand().
     */
    public function testCommand()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_connector::read_socket
     * @todo   Implement testRead_socket().
     */
    public function testRead_socket()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

	/**
	 * @covers steam_connector::add_to_buffer
	 * @todo   Implement testRead_socket().
	 */
	public function testAdd_to_buffer()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
}
