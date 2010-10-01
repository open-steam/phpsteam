<?php
/**
 * steam_date
 *
 * Class definition
 * in sTeam
 *
 * PHP versions 5
 *
 * @package     PHPsTeam
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Alexander Roth <aroth@it-roth.de>, Dominik Niehus <nicke@upb.de>
 */

/**
 *
 * @package     PHPsTeam
 */
class steam_date extends steam_object
{

	/**
 	* constructor of steam_date:
 	* 
 	* @param $pSteamConnector
 	* @param $pID
	*/	
	public function __construct( $pSteamConnectorID, $pID = 0 )
	{
		if (!is_string($pSteamConnectorID)) throw new ParameterException("pSteamConnectorID", "string");
		parent::__construct( $pSteamConnectorID, $pID );
		$this->type = CLASS_DATE;
	}
	
	/**
 	* function get_ical_data:
 	*/
	public function get_ical_data()
	{
		
	}

}
?>