<?php

/**
 * steam_script
 *
 * PHP versions 5
 *
 * @package     PHPsTeam
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Daniel Büse <dbuese@upb.de>, Dominik Niehus <nicke@upb.de>
 *
 */

/**
 *
 * @package     PHPsTeam
 */
class steam_script extends steam_object
{

	/**
	 * constructor of steam_script
	 *
	 * @param $pSteamContainer
	 * @param $pID
	 */
	public function __construct( $pSteamConnectorID, $pID = 0 )
	{
		if (!is_string($pSteamConnectorID)) throw new ParameterException("pSteamConnectorID", "string");
		parent::__construct( $pSteamConnectorID, $pID );
		$this->type = CLASS_SCRIPT;
	}

}

?>