<?php

namespace OpenSteam\Persistence\Downloader;

class InlineDownloader {

    protected static function prepare_header(\steam_document $document)
    {
        header("Pragma: private");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/octet-stream");
        header("Content-Length:" . $document->get_content_size());
        header("Content-Disposition: inline; filename=\"" . $document->get_name() . "\"");
    }
}