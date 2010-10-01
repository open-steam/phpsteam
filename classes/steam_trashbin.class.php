<?php

/**
 * steam_trashbin
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
class steam_trashbin extends steam_container
{

	/**
	 * constructor of steam_trashbin
	 *
	 * @param $pSteamContainer
	 * @param $pID
	 */
	public function __construct( $pSteamConnectorID, $pID = 0 )
	{
		if (!is_string($pSteamConnectorID)) throw new ParameterException("pSteamConnectorID", "string");
		parent::__construct( $pSteamConnectorID, $pID );
		$this->type = CLASS_TRASHBIN;
	}

}

?>