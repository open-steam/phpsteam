<?php

namespace OpenSteam\Persistence\Downloader;

class InlineDownloader extends Downloader
{
    protected static function prepare_header(\steam_document $document, $params = array())
    {
        header("Pragma: private");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: {$document->get_mimetype()}");
        header("Content-Length:" . $document->get_content_size());
        header("Content-Disposition: inline; filename=\"" . $document->get_name() . "\"");
        $document->send_custom_header("IN");
    }
}
