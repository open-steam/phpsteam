<?php

namespace OpenSteam\Persistence\ContentProvider;

abstract class SteamContentProvider {

    public $steam_connector;

    public abstract function get_content(steam_document $document);
}