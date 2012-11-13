<?php

class persistenceTest extends PHPUnit_Framework_TestCase
{
	private static $steamConnector;
	private static $testObjects = array();

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

	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	public function testCreateManyDocuments()
	{
		$currentUser = self::$steamConnector->get_current_steam_user();
		$userHome = $currentUser->get_workroom();
		$content = file_get_contents(dirname(dirname(__FILE__)) . "/data/8mbTest");
		for($i = 0; $i < 10; $i++) {
			$document = steam_factory::create_document(self::$steamConnector->get_id(), $this->initObjName, $content, "", $userHome, $this->initObjDesc);
			self::$testObjects[] = $document;
			$this->assertTrue(($content === $document->get_content()));
		}
	}

	public function testDeleteDocuments() {
		foreach(self::$testObjects as $testObject) {
			$this->assertTrue($testObject->delete());
		}
	}

	/*public function testDeleteAll() {
		$currentUser = self::$steamConnector->get_current_steam_user();
		$userHome = $currentUser->get_workroom();
		$objects = $userHome->get_inventory();
		foreach($objects as $object) {
			if ($object instanceof steam_document) {
				$object->delete();
			}
		}
	}*/

}