<?php

abstract class DocumentDownload
{

    /**
     * @var steam_document
     */
    public $document;

    public $persistence;

    protected abstract function prepare_header();

    public function download()
    {
        $this->prepare_header();
        @ob_flush();
        if (isset($this->persistence)) {
            print $this->persistence->load($this->document);
        }
        else {
            print $this->document->get_content();
        }
    }
}