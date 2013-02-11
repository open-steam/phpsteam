<?php

namespace OpenSteam\Persistence\ContentProvider;

abstract class SteamContentProvider {

    public abstract function getContent(\steam_document $document, $buffer = 0);
	
	public abstract function printContent(\steam_document $document);
}