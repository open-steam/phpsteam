<?php

namespace OpenSteam\Persistence\Downloader;

class AttachmentDownloader extends Downloader {

	protected static function prepare_header(\steam_document $document, $param = array()) {
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Last-Modified: {$document->get_attribute(DOC_LAST_MODIFIED)}");
		header("Content-Type: {$document->get_mimetype()}");
		header("Content-Length: {$document->get_content_size()}");
		header("Content-Disposition: attachment; filename=\"" . $document->get_name() . "\"");
        $document->send_custom_header("A");
	}

}