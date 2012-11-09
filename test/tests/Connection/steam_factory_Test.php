<?php

class steam_factory_Test extends PHPUnit_Framework_TestCase
{

	private static $steamConnector;

	public static function setUpBeforeClass() {
		self::$steamConnector = steam_connector::connect(STEAM_SERVER, STEAM_SERVER_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
	}

	public static function tearDownAfterClass() {
		self::$steamConnector->disconnect();
		self::$steamConnector = null;
	}

    /**
     * @covers steam_factory::get_instance
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
     * @covers steam_factory::get_object
     * @todo   Implement testGet_object().
     */
    public function testGet_object()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::prefetch
     * @todo   Implement testPrefetch().
     */
    public function testPrefetch()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::path_to_object
     * @todo   Implement testPath_to_object().
     */
    public function testPath_to_object()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::get_object_by_name
     * @todo   Implement testGet_object_by_name().
     */
    public function testGet_object_by_name()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::get_user
     * @todo   Implement testGet_user().
     */
    public function testGet_user()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::username_to_object
     * @todo   Implement testUsername_to_object().
     */
    public function testUsername_to_object()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::get_group
     * @todo   Implement testGet_group().
     */
    public function testGet_group()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::groupname_to_object
     */
    public function testGroupname_to_object()
    {
		$steam_group = steam_factory::groupname_to_object(self::$steamConnector->get_id(), "steam");
		$this->assertTrue(is_object($steam_group));
		$this->assertTrue($steam_group instanceof steam_group);
		$this->assertTrue($steam_group->get_name() === "sTeam");
    }

    /**
     * @covers steam_factory::load_attributes
     * @todo   Implement testLoad_attributes().
     */
    public function testLoad_attributes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::get_attributes
     * @todo   Implement testGet_attributes().
     */
    public function testGet_attributes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_copy
     * @todo   Implement testCreate_copy().
     */
    public function testCreate_copy()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_object
     * @todo   Implement testCreate_object().
     */
    public function testCreate_object()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_group
     * @todo   Implement testCreate_group().
     */
    public function testCreate_group()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_container
     * @todo   Implement testCreate_container().
     */
    public function testCreate_container()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_room
     * @todo   Implement testCreate_room().
     */
    public function testCreate_room()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_calendar
     * @todo   Implement testCreate_calendar().
     */
    public function testCreate_calendar()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_link
     * @todo   Implement testCreate_link().
     */
    public function testCreate_link()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_exit
     * @todo   Implement testCreate_exit().
     */
    public function testCreate_exit()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_messageboard
     * @todo   Implement testCreate_messageboard().
     */
    public function testCreate_messageboard()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_wiki
     * @todo   Implement testCreate_wiki().
     */
    public function testCreate_wiki()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_textdoc
     * @todo   Implement testCreate_textdoc().
     */
    public function testCreate_textdoc()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_document
     * @todo   Implement testCreate_document().
     */
    public function testCreate_document()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_docextern
     * @todo   Implement testCreate_docextern().
     */
    public function testCreate_docextern()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::create_user
     * @todo   Implement testCreate_user().
     */
    public function testCreate_user()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::setUserCache
     * @todo   Implement testSetUserCache().
     */
    public function testSetUserCache()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_factory::setGroupCache
     * @todo   Implement testSetGroupCache().
     */
    public function testSetGroupCache()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
