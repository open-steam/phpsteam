<?php

namespace OpenSteam\Persistence\Downloader;

abstract class Downloader {

    //protected abstract static function prepare_header(\steam_document $document, $params = array());

    public static function download(\steam_document $document) {
        static::prepare_header($document);
        @ob_flush();
        $document->print_content();
    }
}