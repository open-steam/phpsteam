<?php

namespace OpenSteam\Persistence\Downloader;

class InlineDownloader {

    protected function prepare_header()
    {
        header("Pragma: private");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/octet-stream");
        header("Content-Length:" . $this->document->get_content_size());
        header("Content-Disposition: inline; filename=\"" . $this->document->get_name() . "\"");
    }
}