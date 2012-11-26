<?php

namespace OpenSteam\Persistence;

abstract class Persistence {
	protected static $_instance = null;

	private function __construct() {

	}

	public static function getInstance()
	{
		$className = get_called_class();

		if (!isset(static::$_instance[$className])) {
			static::$_instance[$className] = new static();
			static::$_instance[$className]->init();
		}
		return static::$_instance[$className];
	}

	private function __clone() {

	}

	public abstract static function init();

    public abstract function delete(\steam_document $document, $buffer = 0);

	public abstract function initialSave(\steam_document $document, &$content);

    public abstract function save(\steam_document $document, &$content, $buffer = 0);

    public abstract function load(\steam_document $document, $buffer = 0);

	public abstract function getSize(\steam_document $document, $buffer = 0);

	public abstract static function getContentProvider();
}