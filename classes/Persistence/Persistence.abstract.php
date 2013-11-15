<?php

namespace OpenSteam\Persistence;

use steam_document;

abstract class Persistence
{
    protected static $_instance = null;

	private function __construct()
    {

	}

	public static function getInstance()
	{
		$className = get_called_class();

		if (!isset(static::$_instance[$className]))
        {
			static::$_instance[$className] = new static();
			static::$_instance[$className]->init();
		}
		return static::$_instance[$className];
	}

	private function __clone()
    {

	}

    //public abstract static function init();

    public abstract function save(steam_document $document, $handle, $buffer = 0);

    public abstract function migrateSave(steam_document $document, $handle);

    public abstract function load(steam_document $document, $buffer = 0);

	public abstract function printContent(steam_document $document);

	public abstract function getSize(steam_document $document, $buffer = 0);

	public abstract function delete(steam_document $document, $buffer = 0);

	//public abstract static function getContentProvider();

	public abstract function low_copy(steam_document $orig, steam_document $copy);
}