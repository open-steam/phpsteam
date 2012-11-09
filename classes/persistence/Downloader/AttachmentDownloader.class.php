<?php

namespace OpenSteam\Persistence\Downloader;

class AttachmentDownloader extends Downloader {

	protected static function prepare_header(\steam_document $document) {
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Last-Modified: {$document->attributes['lastmodified']}");
		header("Content-Type: {$document->attributes['mimetype']}");
		header("Content-Length: {$document->attributes['contentsize']}");
		header("Content-Disposition: attachment; filename=\"" . $document->attributes['name'] . "\"");
	}

}