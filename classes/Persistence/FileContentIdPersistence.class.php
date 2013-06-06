<?php

namespace OpenSteam\Persistence;

class FileContentIdPersistence extends FilePersistence {

	private static $persistenceBaseFolder;

	public static function init() {
		self::$persistenceBaseFolder = FILE_PERSISTENCE_BASE_PATH . "contentid/";
		if (API_DEBUG) {
			if (!is_dir(self::$persistenceBaseFolder)) {
				throw new \Exception("file persistence folder (" . self::$persistenceBaseFolder . ") is missing.");
			}
			if (!is_readable(self::$persistenceBaseFolder)) {
				throw new \Exception("file persistence folder (" . self::$persistenceBaseFolder . ") is not readable.");
			}
			if (!is_writable(self::$persistenceBaseFolder)) {
				throw new \Exception("file persistence folder (" . self::$persistenceBaseFolder . ") is not writable.");
			}
		}
	}

	public function initialSave(\steam_document $document, &$content) {
		$document->steam_command($document, "set_content", array(""), 0);
		return $this->putToFile($document, $content);
	}

	private function putToFile(\steam_document $document, &$content) {
		$dir = $document->get_id();
		$dir = str_pad((string)$dir, 10, "0", STR_PAD_LEFT);
		$dir_array = str_split($dir, 2);

		$target_dir = self::$persistenceBaseFolder;

		foreach ($dir_array as $subdir) {
			$target_dir .= $subdir . "/";
			if(!file_exists($target_dir)){
				mkdir($target_dir);
			}
		}

		$fileName = $document->get_content_id();
		file_put_contents($target_dir . $fileName, $content);
		return strlen($content);
	}

	public function save(\steam_document $document, &$content, $buffer = 0, $noVersion = false) {
		if ($noVersion) {

		} else {
			$document->steam_command($document, "set_content", array(""), 0); //no change, but this will create a version
		}

		$result = $this->putToFile($document, $content);
		if ($buffer) {
			return $document->get_steam_connector()->add_to_buffer($result);
		} else {
			return $result;
		}
    }

	public function migrateSave(\steam_document $document, &$content) {
		//save content to new persistence
		$this->save($document, $content, 0, true);
		//remove old Content from DB
		$this->directDbSave($document, "");
	}

	private function directDbSave(\steam_document $document, &$content) {
		$databaseHelper = \OpenSteam\Helper\DatabaseHelper::getInstance();
		$databaseHelper->connect_to_mysql();
		$databaseHelper->set_content($document->get_content_id(), $content);
		$document->steam_command($document, "update_content_size", array(), 0);
	}

	public function load(\steam_document $document, $buffer = 0) {
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

	public function printContent(\steam_document $document) {
		$module_read_doc = $document->get_steam_connector()->get_module("table:read-documents");
		$document->steam_command($module_read_doc, "download_document", array(8, $document), 0);

		$file_path = $this->get_file_path($document);
		print file_get_contents($file_path);
	}

	public function getSize(\steam_document $document, $buffer = 0) {
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

	public function delete(\steam_document $document, $buffer = 0) {
        $this->lowDeleteContentFile($document);

		//delete versions if not is version
		$version_of = $document->get_attribute(OBJ_VERSIONOF);
        if (!($version_of instanceof \steam_document)) {
            //get versions
            $versions = $document->get_previous_versions();
			foreach ($versions as $version) {
				if(!empty($version)){
					$this->delete($version);
				}

			}
        }
    }

    public function lowDeleteContentFile($document) {
		$contentFile = $this->get_file_path($document);

		if(file_exists($contentFile)){
			unlink($contentFile);
		} else {
			throw new \Exception("content file is missing (id: " . $document->get_id() ."; file: " . $contentFile . ")");
		}

		$current_dir = dirname($contentFile);
		while ($current_dir . "/" !== self::$persistenceBaseFolder) {
			$obj_count = count(glob($current_dir . "/*"));
			if ($obj_count === 0) {
				rmdir($current_dir);
				$current_dir = dirname($current_dir);
			} else {
				break;
			}
		}
	}

    public function get_file_path(\steam_document $document) {
    	$version_of = $document->get_attribute(OBJ_VERSIONOF);
        if ($version_of instanceof \steam_document) {
            $dir = $version_of->get_id();
        } else {
            $dir = $document->get_id();
        }
		$dir = str_pad((string)$dir, 10, "0", STR_PAD_LEFT);
		$dir_array = str_split($dir, 2);

		$target_dir = self::$persistenceBaseFolder;
        foreach ($dir_array as $subdir) {
            $target_dir .= $subdir . "/";
        }

        $target_dir .= $document->get_content_id();
		return $target_dir;
    }

	public static function getContentProvider() {
		return null;
	}
}