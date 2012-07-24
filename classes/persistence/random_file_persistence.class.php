<?php

class RandomFilePersistence extends FilePersistance {

    public function delete(steam_document $document) {
        $version_of = $document->get_attribute(OBJ_VERSIONOF);

        $file_path = $this->get_file_path($document);
        $filesystem_persistence_base_dir = pathinfo(FILESYTEM_PERSISTENCE_BASE_PATH, PATHINFO_DIRNAME);
        if(file_exists($file_path)){
            unlink($file_path);
        }
        

        if (!$version_of) {
            //get versions
            $versions = $document->get_previous_versions();

            foreach ($versions as $version) {
                if(!empty($version)){
                    $this->delete($version);
                }
                
            }
        }

        $current_dir = pathinfo($file_path, PATHINFO_DIRNAME);

        //walk up through directory-tree
        //check if directory contains other files or dirs
        //if not: delete the current directory
        while ($current_dir !== $filesystem_persistence_base_dir) {
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

    public function load(steam_document $document) {
        $file_path = $this->get_file_path($document);
        $file_exists = file_exists($file_path);

        $content = file_get_contents($file_path);

        return $content;
    }

    public function save(steam_document $document, $content) {
        $uuid = $this->generate_id($document, $content);
        $dir_array = str_split($uuid, 3);

        if (!FILESYTEM_PERSISTENCE_BASE_PATH) {
            throw Exception('Have to set persistence base path!');
        }
        $target_dir = FILESYTEM_PERSISTENCE_BASE_PATH . "random/";

        foreach ($dir_array as $subdir) {
            $target_dir .= $subdir . "/";
            if(! file_exists($target_dir)){
                mkdir($target_dir);
            }
        }

        $steam_id = $document->get_id();
        file_put_contents($target_dir . $steam_id, $content);

        //TODO: check file permissions in target directory
        //if can't write - throw exception
        //if could write return uuid else false
        return $uuid;
    }

    public function generate_id(steam_document $document, $content) {
        return $this->generate_id_unwrapped();
    }

    public function generate_id_unwrapped(){
        $id_length = 15;
        $id = "";
        for ($i = 0; $i < $id_length; $i++) {
            $id .= base_convert(mt_rand(0, 15), 10, 16);
        }

        return $id;
    }

    public function get_file_path(steam_document $document) {
        $uuid = $document->get_content(0, true);
        $dir_array = str_split($uuid, 3);

        $target_dir = FILESYTEM_PERSISTENCE_BASE_PATH . "random/";
        $version_of = $document->get_attribute(OBJ_VERSIONOF);

        foreach ($dir_array as $subdir) {
            $target_dir .= $subdir . "/";
        }

        $tmp = $target_dir;

        if ($version_of) {
            $target_dir .= $version_of->get_id();
        } else {
            $target_dir .= $document->get_id();
        }

        if(!file_exists($target_dir)){
            $new_file = $target_dir;
            $old_file = $tmp . "content";
			
			if(file_exists($old_file)){
				rename($old_file, $new_file);  
			} else {
				throw Exception("File not found."); 
			}      
        }

        return $target_dir;
    }
    

    public function get_file_size(steam_document $document, $buffer = 0) {
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

}