<?php

namespace OpenSteam\Persistence;

class DatabasePersistence extends Persistence {

    protected static $_contentProvider;

    public static function init() {
        $class = DEFAULT_CONTENT_PROVIDER;
        self::$_contentProvider = new $class();
    }

    public function delete(\steam_document $document, $buffer = 0) {
        //no additional stuff needed
    }

    public function save(\steam_document $document, &$content, $buffer = 0) {
		unset($document->attributes["DOC_SIZE"]);
		return $document->steam_command($document, "set_content", array($content), $buffer);
    }

    public function load(\steam_document $document, $buffer = 0) {
        return self::$_contentProvider->getContent($document, $buffer);
    }

    public function getSize(\steam_document $document , $buffer = 0) {
		if (($buffer == 0) && isset($document->attributes["DOC_SIZE"])) {
			return $document->attributes["DOC_SIZE"];
		}
        $result = $document->steam_command($document, "get_content_size", array(), $buffer);
        if ($buffer == 0) {
            $document->attributes["DOC_SIZE"] = $result;
        }
        return $result;
    }
}