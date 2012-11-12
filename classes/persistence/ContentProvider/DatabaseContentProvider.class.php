<?php

namespace OpenSteam\Persistence\ContentProvider;

class DatabaseContentProvider extends SteamContentProvider
{
    

    public function getContent(\steam_document $document, $buffer = 0)
    {
        $callback = function(\steam_document $document) {
			$databaseHelper = \OpenSteam\Helper\DatabaseHelper::getInstance();
			$databaseHelper->connect_to_mysql();
			$content = $databaseHelper->get_content($document->get_content_id());
			return $content;
		};

		if ($buffer) {
			$tid = $document->get_steam_connector()->add_to_buffer($document);
			return $document->get_steam_connector()->add_buffer_result_callback($tid, $callback);
		} else {
			return $callback($document);
		}
    }
}