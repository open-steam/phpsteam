<?php

abstract class SteamDocumentDataProvider {

    public $steam_connector;

    public abstract function get_content(steam_document $document);
}