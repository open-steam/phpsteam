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
        $file_path = $this->get_file_path($document);
		if(file_exists($file_path)){
            unlink($file_path);
        }

		$version_of = $document->get_attribute(OBJ_VERSIONOF);
        if (!$version_of) {
            //get versions
            $versions = $document->get_previous_versions();
            
			if(is_array($versions)){
				foreach ($versions as $version) {
					if(!empty($version)){
						$this->delete($version);
					}
				
				}
			}
        }

        $current_dir = pathinfo($file_path, PATHINFO_DIRNAME);
        if(!is_dir($current_dir)){
        	return;
        }


        //walk up through directory-tree
        //check if directory contains other files or dirs
        //if not: delete the current directory
		$file_persistence_base_dir = pathinfo(FILE_PERSISTENCE_BASE_PATH, PATHINFO_DIRNAME);
        while ($current_dir !== $file_persistence_base_dir) {
            $obj_count = count(glob($current_dir . "/*"));

            if ($obj_count === 0) {
                rmdir($current_dir);

                //goto parent directory
                $current_dir = substr($current_dir, 0, strrpos($current_dir, "/"));
            } else {
                break;
            }
        }
    }

    public function save(\steam_document $document, &$content, $buffer = 0) {
        $uuid = $this->generate_id($document, $content);
        $dir_array = str_split($uuid, 3);

        if (!FILE_PERSISTENCE_BASE_PATH) {
            throw Exception('Have to set persistence base path!');
        }
        $target_dir = self::$persistenceBaseFolder;

        foreach ($dir_array as $subdir) {
            $target_dir .= $subdir . "/";
            if(! file_exists($target_dir)){
                mkdir($target_dir);
            }
        }

        $steam_id = $document->get_id();
        file_put_contents($target_dir . $steam_id, $content);
		$document->steam_command($document, "set_content", array($uuid), 0);
        //TODO: check file permissions in target directory
        //if can't write - throw exception
        //if could write return uuid else false
        return strlen($content);
    }

	public function load(\steam_document $document, $buffer = 0) {
		$file_path = $this->get_file_path($document);
		$content = file_get_contents($file_path);

		return $content;
	}

	public function getSize(\steam_document $document, $buffer = 0) {
		$file_path = $this->get_file_path($document);
		if(file_exists($file_path)){
			$file_size = filesize($file_path);
		} else {
			$file_size = 0;
		}

		if($buffer){
			$steam = $document->get_steam_connector();
			$steam_connection = steam_connection::get_instance($steam->get_id());
			$trans_action = $steam_connection->get_transaction_id();
			$steam_connection->add_known_result($trans_action, $file_size);

			return $trans_action;
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

    public function get_file_path(\steam_document $document) {
        $uid = $document->steam_command($document, "get_content", array(), 0);
        $dir_array = str_split($uid, 3);

        $target_dir = self::$persistenceBaseFolder;
        $version_of = $document->get_attribute(OBJ_VERSIONOF);

        foreach ($dir_array as $subdir) {
            $target_dir .= $subdir . "/";
        }

        if ($version_of instanceof \steam_document) {
            $target_dir .= $version_of->get_id();
        } else {
            $target_dir .= $document->get_id();
        }
		return $target_dir;
    }
}