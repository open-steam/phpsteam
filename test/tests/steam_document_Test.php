<?php

require dirname(dirname(__FILE__)) . '/etc/default.def.php';
require dirname(dirname(__FILE__)) . '/deps/vendor/autoload.php';

class steam_document_Test extends PHPUnit_Framework_TestCase
{
	private static $steamConnector;
	private $testObject;

	public static function setUpBeforeClass() {
		self::$steamConnector = steam_connector::connect(STEAM_SERVER, STEAM_SERVER_PORT, STEAM_ROOT_LOGIN, STEAM_ROOT_PW);
	}

	public static function tearDownAfterClass() {
		self::$steamConnector->disconnect();
		self::$steamConnector = null;
	}

    protected function setUp()
    {
		$this->assertTrue(self::$steamConnector->get_login_status());

		//creating object for testing
		$currentUser = self::$steamConnector->get_current_steam_user();
		$userHome = $currentUser->get_workroom();
		$this->testObject = steam_factory::create_document(self::$steamConnector->get_id(), "Test Document.txt", "Hello World!", "text/plain", $userHome, "This is a document for testing.");
	}

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
		//cleanup
		if ($this->testObject instanceof steam_object) {
			$this->testObject->delete();
			unset($this->testObject);
		}
	}

    /**
     * @covers steam_document::get_type
     * @todo   Implement testGet_type().
     */
    public function testGet_type()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::download
     * @todo   Implement testDownload().
     */
    public function testDownload()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::get_reader
     * @todo   Implement testGet_reader().
     */
    public function testGet_reader()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::is_read
     * @todo   Implement testIs_read().
     */
    public function testIs_read()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::set_content
     */
    public function testSet_content()
    {
		$newContent = "Goodbye!";
		$this->testObject->set_content($newContent);
		$this->assertEquals($newContent, $this->testObject->get_content());
    }

    /**
     * @covers steam_document::get_content_size
     * @todo   Implement testGet_content_size().
     */
    public function testGet_content_size()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::get_content_id
     * @todo   Implement testGet_content_id().
     */
    public function testGet_content_id()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::get_content
     * @todo   Implement testGet_content().
     */
    public function testGet_content()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::delete_thumbnail
     * @todo   Implement testDelete_thumbnail().
     */
    public function testDelete_thumbnail()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::delete_thumbnails
     * @todo   Implement testDelete_thumbnails().
     */
    public function testDelete_thumbnails()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::get_thumbnail
     * @todo   Implement testGet_thumbnail().
     */
    public function testGet_thumbnail()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::get_thumbnail_data
     * @todo   Implement testGet_thumbnail_data().
     */
    public function testGet_thumbnail_data()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::get_content_html
     * @todo   Implement testGet_content_html().
     */
    public function testGet_content_html()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::get_version
     * @todo   Implement testGet_version().
     */
    public function testGet_version()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::get_previous_versions
     * @todo   Implement testGet_previous_versions().
     */
    public function testGet_previous_versions()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers steam_document::is_previous_version_of
     * @todo   Implement testIs_previous_version_of().
     */
    public function testIs_previous_version_of()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
