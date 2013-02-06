<?php

namespace OpenSteam\Persistence\ContentProvider;

class CoalContentProvider extends  SteamContentProvider {

    public function getContent(\steam_document $document, $buffer = 0) {
        return $document->steam_command(
            $document,
            "get_content",
            array(),
			$buffer
        );
    }
}