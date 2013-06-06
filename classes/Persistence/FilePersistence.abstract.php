<?php

namespace OpenSteam\Persistence;

abstract class FilePersistence extends Persistence {

	//public abstract function generate_id(&$content);

	public abstract function get_file_path(\steam_document $document);
}
