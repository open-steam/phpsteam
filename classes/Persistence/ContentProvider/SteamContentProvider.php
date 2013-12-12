<?php

namespace OpenSteam\Persistence\ContentProvider;

abstract class SteamContentProvider
{
    abstract public function getContent(\steam_document $document, $buffer = 0);

    abstract public function printContent(\steam_document $document);
}
