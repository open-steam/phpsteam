<?php

namespace OpenSteam\Persistence\ContentProvider;

class CoalContentProvider extends  SteamContentProvider {

    public function get_content(steam_document $document) {
        return $document->steam_command(
            $document,
            "get_content",
            array(),
            0
        );
    }
}