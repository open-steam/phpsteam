<?php

namespace OpenSteam\Persistence;

class FileUidPersistence extends FilePersistence {

	private static $persistenceBaseFolder;

	public static function init() {
		self::$persistenceBaseFolder = FILE_PERSISTENCE_BASE_PATH . "uid/";
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

	public function initialSave(\steam_document $document, &$content) {
		$uid = $this->generate_id($document, $content);
		$this->directDbSave($document, $uid);
		return $this->putToFile($document, $uid, $content);
	}

    public function save(\steam_document $document, &$content, $buffer = 0, $noVersion = false) {
		$uid = $this->get_uid($document);

		if ($noVersion) {
			$this->directDbSave($document, $uid);
		} else {
			$document->steam_command($document, "set_content", array($uid), 0); //no change, but this will create a version
		}

		$this->putToFile($document, $uid, $content);
		if ($buffer) {
			return $document->get_steam_connector()->add_to_buffer(strlen($content));
		} else {
			return strlen($content);
		}
    }

	private function directDbSave(\steam_document $document, &$content) {
		$databaseHelper = \OpenSteam\Helper\DatabaseHelper::getInstance();
		$databaseHelper->connect_to_mysql();
		$databaseHelper->set_content($document->get_content_id(), $content);
		$document->steam_command($document, "update_content_size", array(), 0);
	}

	private function putToFile(\steam_document $document, $uid, &$content) {
		$dir_array = str_split($uid, 3);

		if (!FILE_PERSISTENCE_BASE_PATH) {
			throw \Exception('Have to set persistence base path!');
		}
		$target_dir = self::$persistenceBaseFolder;

		foreach ($dir_array as $subdir) {
			$target_dir .= $subdir . "/";
			if(! file_exists($target_dir)){
				mkdir($target_dir);
			}
		}
		$steam_id = $document->get_id();
		$content_id = $document->get_content_id();
		file_put_contents($target_dir . $steam_id . "-" . $content_id, $content);
	}

	public function load(\steam_document $document, $buffer = 0) {
		$file_path = $this->get_file_path($document);
		$content = file_get_contents($file_path);
		if ($buffer) {
			return $document->get_steam_connector()->add_to_buffer($content);
		} else {
			return $content;
		}
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

    public function generate_id(&$content) {
        return $this->generate_id_unwrapped();
    }

    private function generate_id_unwrapped(){
        $id_length = 15;
        $id = "";
        for ($i = 0; $i < $id_length; $i++) {
            $id .= base_convert(mt_rand(0, 15), 10, 16);
        }

        return $id;
    }

	public function isValidUid($uid) {
		return preg_match("/^[a-f0-9]{15}$/is", $uid);
	}

	public function get_uid(\steam_document $document) {
		return $document->steam_command($document, "get_content", array(), 0);
	}

    public function get_file_path(\steam_document $document) {
        $uid = $this->get_uid($document);
		if (!$this->isValidUid($uid)) {
			throw new \Exception('this is not a uid: ' . $uid);
		}
        $dir_array = str_split($uid, 3);
		$target_dir = self::$persistenceBaseFolder;
        foreach ($dir_array as $subdir) {
            $target_dir .= $subdir . "/";
        }
		$version_of = $document->get_attribute(OBJ_VERSIONOF);
        if ($version_of instanceof \steam_document) {
            $target_dir .= $version_of->get_id() . "-" . $document->get_content_id();
        } else {
            $target_dir .= $document->get_id() . "-" . $document->get_content_id();
        }
		return $target_dir;
    }

	public static function getContentProvider() {
		return null;
	}
}