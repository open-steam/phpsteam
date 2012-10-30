<?php

namespace OpenSteam\Persistence;

class DatabasePersistence extends Persistence {

    protected $data_provider;

    public function __construct(){
        /*if(USE_DATABASE_DOWNLOAD){
            $class = "DatabaseSteamDocumentDataProvider";
        } else {*/
            $class = DEFAULT_STEAM_DATA_PROVIDER;
        //}

        $this->data_provider = new $class();
    }

    public function get_data_provider(){
        return $this->data_provider;
    }

    public function set_data_provider($data_provider){
        $this->data_provider = $data_provider;
    }

    public function delete(steam_document $document) {
        //no additional stuff needed
    }

    public function save(steam_document $document, $content) {
        if( is_resource($content)){
            $content = stream_get_contents($content);
        }
        
        return $document->steam_command(
            $document, "set_content", array($content), 0
        );
    }

    public function load(steam_document $document) {
        return $this->data_provider->get_content($document);
    }

    public function get_file_size(steam_document $document , $buffer = 0) {
        $result = $document->steam_command(
            $document, "get_content_size", array(), $buffer
        );
        if ($buffer == 0) {
            $document->attributes["DOC_SIZE"] = $result;
        }
        return $result;
    }
}