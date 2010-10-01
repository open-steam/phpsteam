<?php

/**
 * steam_exit
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
class steam_exit extends steam_link
{

	/**
	 * constructor of steam_exit:
	 *
	 * @param $pID
	 * @param $pSteamConnector
	 */
	public function __construct($pSteamConnectorID, $pID = "0")
	{
		if (!is_string($pSteamConnectorID)) throw new ParameterException("pSteamConnectorID", "string");
		parent::__construct($pSteamConnectorID, $pID);
		$this->type = CLASS_EXIT;
	}

	/**
	 * function get_exit:
	 *
	 * @param $pBuffer
	 *
	 * @return
	 */
	public function get_exit($pBuffer = 0)
	{
		return $this->steam_command(
		$this,
			"get_exit",
		array(),
		$pBuffer
		);
	}
}
?>