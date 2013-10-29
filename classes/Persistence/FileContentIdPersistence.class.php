<?php

namespace OpenSteam\Persistence;

use steam_document, Exception;

class FileContentIdPersistence extends FilePersistence {

	private static $persistenceBaseFolder;

	public static function init() {
		self::$persistenceBaseFolder = FILE_PERSISTENCE_BASE_PATH . "contentid/";
		if (API_DEBUG) {
			if (!is_dir(self::$persistenceBaseFolder)) {
				throw new Exception("file persistence folder (" . self::$persistenceBaseFolder . ") is missing.");
			}
			if (!is_readable(self::$persistenceBaseFolder)) {
				throw new Exception("file persistence folder (" . self::$persistenceBaseFolder . ") is not readable.");
			}
			if (!is_writable(self::$persistenceBaseFolder)) {
				throw new Exception("file persistence folder (" . self::$persistenceBaseFolder . ") is not writable.");
			}
		}
	}

	private static function createTargetFolderFromID($id) {
		$dir = str_pad((string)$id, 10, "0", STR_PAD_LEFT);
		$dir_array = str_split($dir, 2);

		$target_dir = self::$persistenceBaseFolder;

		foreach ($dir_array as $subdir) {
			$target_dir .= $subdir . "/";
			if(!file_exists($target_dir)){
				mkdir($target_dir);
			}
		}
		return $target_dir;
	}

	private function putToFile(steam_document $document, $handler, $lastContentId, $version) {
		$id = $this->getSaveId($document);
		$target_dir = self::createTargetFolderFromID($id);

		$fileName = $document->get_content_id();
		$contentFile = $target_dir . $fileName;
		if (file_exists($contentFile)) {
			throw new Exception("content file already exists (id: " . $document->get_id() ."; file: " . $contentFile . ")");
		}
		file_put_contents($contentFile, $handler);

		//move old version
		if ($version && ($lastContentId !== 0)) { // if version created and not initial save
			$last_version = $document->get_last_version();
			$versionId = $this->getSaveId($last_version);
			$version_target_dir = self::createTargetFolderFromID($versionId);
			$versionFileName = $last_version->get_content_id();
			if ($versionFileName !== $lastContentId) {
				throw new Exception("wrong Content id in version!!", 1);
			}
			$versionContentFile = $version_target_dir . $versionFileName;
			if (file_exists($versionContentFile)) {
				throw new Exception("content file already exists (id: " . $last_version->get_id() ."; file: " . $versionContentFile . ")");
			}
			rename($target_dir . $lastContentId, $versionContentFile);
		}

		return filesize($contentFile);
	}

	public function save(steam_document $document, $handler, $buffer = 0, $noVersion = false) {
		$version = !$noVersion; //more readable code
		$currentContentId = $document->get_content_id();
		if ($currentContentId === 0) { //initial save
			$version = true;
		}

		if ($version) {
			$document->steam_command($document, "set_content", array(""), 0); //no change, but this will create a version
		}

		$result = $this->putToFile($document, $handler, $currentContentId, $version);
		if ($buffer) {
			return $document->get_steam_connector()->add_to_buffer($result);
		} else {
			return $result;
		}
    }

	public function migrateSave(steam_document $document, $handle) {
		//save content to new persistence
		$this->save($document, $handle, 0, true);

		//remove old Content from DB
		$newContent = "";
		$this->directDbSave($document, $newContent);
	}

	private function directDbSave(steam_document $document, &$content) {
		$databaseHelper = \OpenSteam\Helper\DatabaseHelper::getInstance();
		$databaseHelper->connect_to_mysql();
		$databaseHelper->set_content($document->get_content_id(), $content);
		$document->steam_command($document, "update_content_size", array(), 0);
	}

	public function load(steam_document $document, $buffer = 0) {
		$module_read_doc = $document->get_steam_connector()->get_module("table:read-documents");
		$document->steam_command($module_read_doc, "download_document", array(8, $document), 0);

		$file_path = $this->get_file_path($document);
		$content = file_get_contents($file_path);
		if ($buffer) {
			return $document->get_steam_connector()->add_to_buffer($content);
		} else {
			return $content;
		}
	}

	public function printContent(steam_document $document) {
		$module_read_doc = $document->get_steam_connector()->get_module("table:read-documents");
		$document->steam_command($module_read_doc, "download_document", array(8, $document), 0);

		$file_path = $this->get_file_path($document);
		if (file_exists($file_path)) {
			readfile($file_path);
		} else {
			throw new Exception("content file is missing (id: " . $document->get_id() ."; file: " . $file_path . ")");
		}
	}

	public function getSize(steam_document $document, $buffer = 0) {
		$file_path = $this->get_file_path($document);
		if(file_exists($file_path)){
			$file_size = filesize($file_path);
		} else {
			$file_size = 0;
		}

		if($buffer){
			return $document->get_steam_connector()->add_to_buffer($file_size);
		}  else {
			return $file_size;
		}
	}

	public function delete(steam_document $document, $buffer = 0) {
        return $this->lowDeleteContentFile($document);
    }

    public function lowDeleteContentFile(steam_document $document) {
		$contentFile = $this->get_file_path($document);

		if (!is_string($contentFile)) { // no content file, document never saved
			return true;
		}

		if(file_exists($contentFile)){
			unlink($contentFile);
		} else {
			//echo "***********CONTENTFILE MISSING**************\n";
			//throw new Exception("content file is missing (id: " . $document->get_id() ."; file: " . $contentFile . ")");
		}

		$current_dir = dirname($contentFile);
		if (is_dir($current_dir)) {
			while ($current_dir . "/" !== self::$persistenceBaseFolder) {
				$obj_count = count(glob($current_dir . "/*"));
				if ($obj_count === 0) {
					rmdir($current_dir);
					$current_dir = dirname($current_dir);
				} else {
					break;
				}
			}
		} else {
			//echo "***********NOT A DIR**************\n";
		}
		return true;
	}

	private function getSaveId(steam_document $document) {
		$id = $document->get_id();
		/*$version_of = $document->get_attribute(OBJ_VERSIONOF);
        if ($version_of instanceof steam_document) {
            $id = $version_of->get_id();
        } else {
            $id = $document->get_id();
        }*/
        return $id;
	}

    public function get_file_path(steam_document $document) {
    	$dir = $this->getSaveId($document);
		$dir = str_pad((string)$dir, 10, "0", STR_PAD_LEFT);
		$dir_array = str_split($dir, 2);

		$target_dir = self::$persistenceBaseFolder;
        foreach ($dir_array as $subdir) {
            $target_dir .= $subdir . "/";
        }

        $contentid = $document->get_content_id();
        if ($contentid === 0) {
        	return null;
        }

        $target_dir .= $document->get_content_id();
		return $target_dir;
    }

	public static function getContentProvider() {
		return null;
	}

	public function low_copy(steam_document $orig, steam_document $copy) {
		$this->save($copy, $orig->get_content(), 0, true);
	}
}