<?php

namespace OpenSteam\Persistence;

abstract class Persistence
{

	protected $document;

	public function __construct(steam_document $document) {
		$this->document = $document;
	}

    public abstract function delete();

    public abstract function save(&$content, $buffer);

    public abstract function load($buffer);
}