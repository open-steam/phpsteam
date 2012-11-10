<?php

class steam_document_Test extends PHPUnit_Framework_TestCase
{
	private static $steamConnector;
	private $testObject;

	private $initObjName = "Test Document.txt";
	private $initContent = "Hello World!";
	private $initMimeType = "text/plain";
	private $initObjDesc = "This is a document for testing.";

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
		$this->testObject = steam_factory::create_document(self::$steamConnector->get_id(), $this->initObjName, $this->initContent, $this->initMimeType, $userHome, $this->initObjDesc);
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
     */
    public function testGet_type()
    {
        $objectClass = $this->testObject->get_object_class();
		$this->assertTrue(($objectClass & $this->testObject->get_type()) == $this->testObject->get_type());
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
     * @covers steam_document::get_readers
     */
    public function testGet_readers()
    {
		$readersArray = $this->testObject->get_readers();
		$this->assertTrue(is_array($readersArray));
		$this->assertTrue(empty($readersArray));
		$this->testObject->get_content();
		$readersArray = $this->testObject->get_readers();
		$this->assertTrue(sizeof($readersArray) === 1);
		$this->assertEquals(self::$steamConnector->get_current_steam_user(), $readersArray[0]);

		//reset test object
		$this->tearDown();
		$this->setUp();

		//test with buffer
		$tid = $this->testObject->get_readers(true);
		$result = self::$steamConnector->buffer_flush();
		$readersArray = $result[$tid];
		$this->assertTrue(is_array($readersArray));
		$this->assertTrue(empty($readersArray));
		$this->testObject->get_content(true);
		$tid = $this->testObject->get_readers(true);
		$result = self::$steamConnector->buffer_flush();
		$readersArray = $result[$tid];
		$this->assertTrue(sizeof($readersArray) === 1);
		$this->assertEquals(self::$steamConnector->get_current_steam_user(), $readersArray[0]);
    }

    /**
     * @covers steam_document::is_reader
	 * @todo test with different user
     */
    public function testIs_reader()
    {
        $this->assertEquals(0, $this->testObject->is_reader());
		$this->testObject->get_content();
		$this->assertEquals(1, $this->testObject->is_reader());

		//reset test object
		$this->tearDown();
		$this->setUp();

		//test with buffer
		$tid = $this->testObject->is_reader("", true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals(0, $result[$tid]);
		$this->testObject->get_content(true);
		$tid = $this->testObject->is_reader("", true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals(1, $result[$tid]);
    }

    /**
     * @covers steam_document::set_content
     */
    public function testSet_content()
    {
		$newContent = "Goodbye!";
		$this->assertEquals(strlen($newContent), $this->testObject->set_content($newContent));
		$this->assertEquals($newContent, $this->testObject->get_content());

		//reset test object
		$this->tearDown();
		$this->setUp();

		//test with buffer
		$newContent = "Goodbye!";
		$tid = $this->testObject->set_content($newContent, true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals(strlen($newContent), $result[$tid]);
		$tid = $this->testObject->get_content(true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals($newContent, $result[$tid]);
    }

    /**
     * @covers steam_document::get_content_size
     */
    public function testGet_content_size()
    {
        $this->assertEquals(strlen($this->initContent), $this->testObject->get_content_size());

		//no reset needed

		//test with buffer
		$tid = $this->testObject->get_content_size(true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals(strlen($this->initContent), $result[$tid]);
    }

    /**
     * @covers steam_document::get_content_id
     */
    public function testGet_content_id()
    {
		$firstContentId = $this->testObject->get_content_id();
		$this->testObject->set_content("Dog");
		$secondContentId = $this->testObject->get_content_id();
		$this->assertTrue(is_int($firstContentId));
		$this->assertTrue(is_int($secondContentId));
		$this->assertTrue($firstContentId < $secondContentId);

		//reset test object
		$this->tearDown();
		$this->setUp();

		//test with buffer
		$tid = $this->testObject->get_content_id(true);
		$result = self::$steamConnector->buffer_flush();
		$firstContentId = $result[$tid];
		$this->testObject->set_content("Dog", true);
		$tid = $this->testObject->get_content_id(true);
		$result = self::$steamConnector->buffer_flush();
		$secondContentId = $result[$tid];
		$this->assertTrue(is_int($firstContentId));
		$this->assertTrue(is_int($secondContentId));
		$this->assertTrue($firstContentId < $secondContentId);
    }

    /**
     * @covers steam_document::get_content
     */
    public function testGet_content()
    {
		$newContent = "Goodbye!";
		$this->testObject->set_content($newContent);
		$this->assertEquals($newContent, $this->testObject->get_content());

		//reset test object
		$this->tearDown();
		$this->setUp();

		//test with buffer
		$newContent = "Goodbye!";
		$this->testObject->set_content($newContent, true);
		$tid = $this->testObject->get_content(true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals($newContent, $result[$tid]);
    }

    /**
     * @covers steam_document::get_content_html
	 * @todo test with a real wiki!!
     */
    public function testGet_content_html()
    {
		$this->assertEquals("<!-- wiki: Source Document is not a wiki file !-->\n", $this->testObject->get_content_html());

		//test with buffer
		$tid = $this->testObject->get_content_html();
		$this->assertEquals("<!-- wiki: Source Document is not a wiki file !-->\n", $result[$tid]);
    }

    /**
     * @covers steam_document::get_version
     */
    public function testGet_version()
    {
        $this->assertEquals(1, $this->testObject->get_version());
		$this->testObject->set_content("Dog");
		$this->assertEquals(2, $this->testObject->get_version());

		//reset test object
		$this->tearDown();
		$this->setUp();

		//test with buffer
		$tid = $this->testObject->get_version(true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals(1, $result[$tid]);
		$this->testObject->set_content("Dog", true);
		$tid = $this->testObject->get_version(true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals(2, $result[$tid]);
    }

    /**
     * @covers steam_document::get_previous_versions
     */
    public function testGet_previous_versions()
    {
		$this->assertEquals(array(), $this->testObject->get_previous_versions());
		$this->testObject->set_content("Dog");
		$versions = $this->testObject->get_previous_versions();
		$this->assertEquals(1, sizeof($versions));
		$this->assertEquals($this->initContent, $versions[0]->get_content());

		//reset test object
		$this->tearDown();
		$this->setUp();

		//test with buffer
		$tid = $this->testObject->get_previous_versions(true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals(array(), $result[$tid]);
		$this->testObject->set_content("Dog", true);
		$tid = $this->testObject->get_previous_versions(true);
		$result = self::$steamConnector->buffer_flush();
		$versions = $result[$tid];
		$this->assertEquals(1, sizeof($versions));
		$tid = $versions[0]->get_content();
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals($this->initContent, $result[$tid]);
    }

    /**
     * @covers steam_document::is_previous_version_of
     */
    public function testIs_previous_version_of()
    {
		$this->assertFalse($this->testObject->is_previous_version_of());
		$this->testObject->set_content("Dog");
		$versions = $this->testObject->get_previous_versions();
		$this->assertEquals($this->testObject, $versions[0]->is_previous_version_of());

		//reset test object
		$this->tearDown();
		$this->setUp();

		//test with buffer
		$tid = $this->testObject->is_previous_version_of(true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertFalse($result[$tid]);
		$this->testObject->set_content("Dog", true);
		$tid = $this->testObject->get_previous_versions(true);
		$result = self::$steamConnector->buffer_flush();
		$versions = $result[$tid];
		$tid = $versions[0]->is_previous_version_of(true);
		$result = self::$steamConnector->buffer_flush();
		$this->assertEquals($this->testObject, $result[$tid]);
    }
}