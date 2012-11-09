<?php

namespace OpenSteam\Persistence\Downloader;

abstract class Downloader {

    protected abstract static function prepare_header(\steam_document $document);

    public static function download(\steam_document $document) {
        self::prepare_header($document);
        @ob_flush();
        print $document->get_content();
    }
}