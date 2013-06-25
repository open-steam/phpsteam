<?php

namespace OpenSteam\Persistence;

class DatabasePersistence extends Persistence {

    protected static $_contentProvider;

    public static function init() {
    	if (DEFAULT_CONTENT_PROVIDER === CONTENT_PROVIDER_COAL) {
			self::$_contentProvider = new \OpenSteam\Persistence\ContentProvider\CoalContentProvider();
	  	} else if (DEFAULT_CONTENT_PROVIDER === CONTENT_PROVIDER_STEAMWEB) {
			self::$_contentProvider = new \OpenSteam\Persistence\ContentProvider\SteamWebContentProvider();
		} else if (DEFAULT_CONTENT_PROVIDER === CONTENT_PROVIDER_DATABASE) {
			self::$_contentProvider = new \OpenSteam\Persistence\ContentProvider\DatabaseContentProvider();
		} else {
			self::$_contentProvider = new \OpenSteam\Persistence\ContentProvider\CoalContentProvider();
		}

    }

    public function delete(\steam_document $document, $buffer = 0) {
        //no additional stuff needed
    }

	public function initialSave(\steam_document $document, &$content) {
		$this->save($document, $content);
	}

	public function migrateSave(\steam_document $document, &$content) {
		$this->save($document, $content, 0, true);
	}

    public function save(\steam_document $document, &$content, $buffer = 0, $noVersion = false) {
		if ($noVersion) {
			$databaseHelper = \OpenSteam\Helper\DatabaseHelper::getInstance();
			$databaseHelper->connect_to_mysql();
			$databaseHelper->set_content($document->get_content_id(), $content);
			$document->steam_command($document, "update_content_size", array(), 0);
			if ($buffer) {
				return $document->get_steam_connector()->add_to_buffer(strlen($content));
			} else {
				return strlen($content);
			}
		} else {
			return $document->steam_command($document, "set_content", array($content), $buffer);
		}
    }

    public function load(\steam_document $document, $buffer = 0) {
        return self::$_contentProvider->getContent($document, $buffer);
    }

	public function printContent(\steam_document $document) {
		self::$_contentProvider->printContent($document);
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

	public static function getContentProvider() {
		return self::$_contentProvider;
	}

    public function low_copy(\steam_document $orig, \steam_document $copy) {
        //nothing to do
    }
}