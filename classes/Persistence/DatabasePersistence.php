<?php

namespace OpenSteam\Persistence;

use steam_document;

class DatabasePersistence extends Persistence {
	protected static $_contentProvider;

	private $cache = [];

	public static function init() {
		if (DEFAULT_CONTENT_PROVIDER === CONTENT_PROVIDER_COAL) {
			self::$_contentProvider = new \OpenSteam\Persistence\ContentProvider\CoalContentProvider();
		} elseif (DEFAULT_CONTENT_PROVIDER === CONTENT_PROVIDER_STEAMWEB) {
			self::$_contentProvider = new \OpenSteam\Persistence\ContentProvider\SteamWebContentProvider();
		} elseif (DEFAULT_CONTENT_PROVIDER === CONTENT_PROVIDER_DATABASE) {
			self::$_contentProvider = new \OpenSteam\Persistence\ContentProvider\DatabaseContentProvider();
		} else {
			self::$_contentProvider = new \OpenSteam\Persistence\ContentProvider\CoalContentProvider();
		}

	}

	public function delete(steam_document $document, $buffer = 0) {
		//no additional stuff needed
	}

	public function allowed(\steam_document $document) {
		return true;
	}

	public function migrateSave(steam_document $document, $handle) {
		$this->save($document, $handle, 0, true);
	}

	public function save(steam_document $document, $handle, $buffer = 0, $noVersion = false) {
		if (is_resource($handle)) {
			$content = stream_get_contents($handle);
		} else {
			$content = $handle;
		}
		if ($noVersion) {
			$databaseHelper = \OpenSteam\Helper\DatabaseHelper::getInstance();
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

	public function load(steam_document $document, $buffer = 0) {
		return self::$_contentProvider->getContent($document, $buffer);
	}

	public function printContent(steam_document $document) {
		self::$_contentProvider->printContent($document);
	}

	public function getSize(steam_document $document, $buffer = 0) {
		if (($buffer === 0) && isset($this->cache[$document->get_id()]) && isset($this->cache[$document->get_id()]["get_content_size"])) {
			return $this->cache[$document->get_id()]["get_content_size"];
		}
		$result = $document->steam_command($document, "get_content_size", array(), $buffer);
		if ($buffer === 0) {
		    if (!isset($this->cache[$document->get_id()])) {
                $this->cache[$document->get_id()] = [];
            }
            $this->cache[$document->get_id()]["get_content_size"] = $result;
		}

		return $result;
	}

	public static function getContentProvider() {
		return self::$_contentProvider;
	}

	public function low_copy(steam_document $orig, steam_document $copy) {
		//nothing to do
	}
}
