<?php

class HashFilePersistence extends FilePersistance {

    public function generate_id(steam_document $document, $content) {
        throw new Exception("Not implemented yet!");
        /*$hash = hash('sha1', $content);

        return $hash;*/
    }

    public function get_file_path(steam_document $document) {
        throw new Exception("Not implemented yet!");
    }

    public function get_file_size(steam_document $document, $buffer = 0) {
        throw new Exception("Not implemented yet!");
    }

}