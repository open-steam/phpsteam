<?php

abstract class DocumentPersistence
{

    public abstract function delete(steam_document $document);

    public abstract function save(steam_document $document, $content);

    public abstract function load(steam_document $document);
}