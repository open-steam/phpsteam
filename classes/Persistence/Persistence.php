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

        if (!isset(static::$_instance[$className])) {
            static::$_instance[$className] = new static();
            static::$_instance[$className]->init();
        }

        return static::$_instance[$className];
    }

    private function __clone()
    {

    }

    abstract public function save(steam_document $document, $handle, $buffer = 0);

    abstract public function migrateSave(steam_document $document, $handle);

    abstract public function load(steam_document $document, $buffer = 0);

    abstract public function printContent(steam_document $document);

    abstract public function getSize(steam_document $document, $buffer = 0);

    abstract public function delete(steam_document $document, $buffer = 0);

    abstract public function low_copy(steam_document $orig, steam_document $copy);
}
