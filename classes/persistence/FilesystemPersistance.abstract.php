<?php

namespace OpenSteam\Persistence;

abstract class FilesytemPersistance extends Persistence {

	public abstract function generate_id(&$content);

	public abstract function get_file_path();

	public abstract function get_file_size($buffer); //buffer needed to be backward-compatible
}
