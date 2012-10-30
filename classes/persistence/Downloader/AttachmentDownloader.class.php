<?php

namespace OpenSteam\Persistence\Downloader;

class AttachmentDownloader extends Downloader {

	protected function prepare_header() {
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Last-Modified: {$this->document->attributes['lastmodified']}");
		header("Content-Type: {$this->document->attributes['mimetype']}");
		header("Content-Length: {$this->document->attributes['contentsize']}");
		header("Content-Disposition: attachment; filename=\"" . $this->document->attributes['name'] . "\"");
	}

}