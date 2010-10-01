<?php

/**
 * steam_room
 *
 * PHP versions 5
 *
 * @package     PHPsTeam
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Alexander Roth <aroth@it-roth.de>, Dominik Niehus <nicke@upb.de>
 *
 */

/**
 *
 * @package     PHPsTeam
 */
class steam_room extends steam_container
{

	/**
	 * constructor of steam_room:
	 *
	 * @param $pID
	 * @param $pSteamConnector
	 */
	public function __construct($pSteamConnectorID,  $pID = "0")
	{
		if (!is_string($pSteamConnectorID)) throw new ParameterException("pSteamConnectorID", "string");
		parent::__construct($pSteamConnectorID, $pID);
		$this->type = CLASS_ROOM;
	}

	/**
	 * function get_visitors:
	 *
	 * Returns the user visiting this room
	 * @return mixed Array of steam_users
	 */
	public function get_visitors()
	{
		return $this->get_inventory(
		CLASS_USER
		);
	}


}

?>