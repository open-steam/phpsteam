<?php

class CoalSteamDocumentDataProvider extends  SteamDocumentDataProvider {

    public function get_content(steam_document $document) {
        return $document->steam_command(
            $document,
            "get_content",
            array(),
            0
        );
    }
}