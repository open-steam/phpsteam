<?php

namespace OpenSteam\Persistence;

abstract class FilePersistence extends Persistence {

	//public abstract function generate_id(&$content);

	public abstract function get_file_path(\steam_document $document);

    public static function allowed($pMimeType) {
        if (strpos($pMimeType, "text") !== false || strpos($pMimeType, "xml") !== false || strpos($pMimeType, "pike") !== false) { //text documents should be persisted in database
            return false;
        } else {
            return true;
        }
    }
}
