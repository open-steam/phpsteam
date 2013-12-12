<?php

namespace OpenSteam\Persistence;

abstract class FilePersistence extends Persistence
{
    abstract public function get_file_path(\steam_document $document);

    public function allowed(\steam_document $document)
    {
        $pMimeType = $document->get_attribute(DOC_MIME_TYPE);

        return self::allowedMimetype($pMimeType);
    }

    public static function allowedMimetype($pMimeType)
    {
        if (strpos($pMimeType, "text") !== false || strpos($pMimeType, "xml") !== false || strpos($pMimeType, "pike") !== false) { //text documents should be persisted in database

            return false;
        } else {
            return true;
        }
    }
}
