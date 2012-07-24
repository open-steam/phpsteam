<?php

abstract class FilePersistance extends DocumentPersistence {

	public abstract function generate_id(steam_document $document, $content);

	public abstract function get_file_path(steam_document $document);

	public abstract function get_file_size(steam_document $document, $buffer = 0);
}
