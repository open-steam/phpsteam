<?php
/**
 * Implements the steam_wiki class
 *
 * Longer description follows
 *
 * PHP versions 5
 * @version CVS: $Id: steam_wiki.class.php,v 1.2 2010/07/21 15:36:51 nicke Exp $
 * @package PHPsTeam
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Alexander Roth <aroth@it-roth.de>, Dominik Niehus <nicke@upb.de>
 */

/**
 *
 * @package     PHPsTeam
 */
class steam_wiki extends steam_document
{

	/**
	 * constructor of steam_wiki
	 *
	 * @param $pID
	 * @param $pSteamConnector
	 */
	public function __construct($pSteamConnectorID,  $pID = "0")
	{
		if (!is_string($pSteamConnectorID)) throw new ParameterException("pSteamConnectorID", "string");
		parent::__construct($pSteamConnectorID, $pID);
		$this->type = CLASS_DOCWIKI;
	}
}

?>