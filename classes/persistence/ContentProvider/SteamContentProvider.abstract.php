<?php

namespace OpenSteam\Persistence\ContentProvider;

abstract class SteamContentProvider {

    public abstract function getContent(\steam_document $document, $buffer = 0);
}