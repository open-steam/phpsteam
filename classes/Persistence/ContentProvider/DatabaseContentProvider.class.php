<?php

namespace OpenSteam\Persistence\ContentProvider;

class DatabaseContentProvider extends SteamContentProvider
{


    public function getContent(\steam_document $document, $buffer = 0)
    {
        $callback = function(\steam_document $document) {
			$databaseHelper = \OpenSteam\Helper\DatabaseHelper::getInstance();
			$databaseHelper->connect_to_mysql();
			return $databaseHelper->get_content($document->get_content_id());
		};

		$module_read_doc = $document->get_steam_connector()->get_module("table:read-documents");
		$document->steam_command($module_read_doc, "download_document", array(8, $document), 0);

		if ($buffer) {
			$tid = $document->get_steam_connector()->add_to_buffer($document);
			$document->get_steam_connector()->add_buffer_result_callback($tid, $callback);
			return $tid;
		} else {

			return $callback($document);
		}
    }

	public function printContent(\steam_document $document) {
		$databaseHelper = \OpenSteam\Helper\DatabaseHelper::getInstance();
		$databaseHelper->connect_to_mysql();
		$module_read_doc = $document->get_steam_connector()->get_module("table:read-documents");
		$document->steam_command($module_read_doc, "download_document", array(8, $document), 0);
		$databaseHelper->print_content($document->get_content_id());
	}
}