<?php

/**
 * steam_database
 *
 * PHP versions 8.1
 *
 * @package     PHPsTeam
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Dominik Niehus <nicke@upb.de>
 * @copyright   2000-2022 Dominik Niehus <nicke@upb.de>
 *
 */

/**
 *
 * @package     PHPsTeam
 */

use OpenSteam\Helper\LoggerHelper;

class steam_database {
	/**
	 * Unique id for this object inside the virtual space, which is
	 * assigned by a sTeam-server.
	 */
	protected $id;

	/**
	 * ID of steam_connector. Connection to sTeam-server
	 */
	public $steam_connectorID;

	public function __construct($steamFactory, $steamConnectorId, $id) {
		if (!($steamFactory instanceof steam_factory)) {
			LoggerHelper::getInstance()->getLogger()->addError("phpsteam error: only steam_factory is allowed to call");
			throw new Exception("phpsteam error: only steam_factory is allowed to call");
		}
		$this->id = $id;
		$this->steam_connectorID = $steamConnectorId;
	}

	public function get_type() {
		return CLASS_DATABASE;
	}
}