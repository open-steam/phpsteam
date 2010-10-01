<?php

/**
 * steam_docextern
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
class steam_docextern extends steam_object
{

	/**
	 * constructor of steam_docextern:
	 * 
	 * @param $pID
	 * @param $pSteamConnector
	 */	
	public function __construct($pSteamConnectorID, $pID = 0)
	{
		if (!is_string($pSteamConnectorID)) throw new ParameterException("pSteamConnectorID", "string");
		parent::__construct($pSteamConnectorID, $pID);
		$this->type = CLASS_DOCEXTERN;
	}

	/**
 	* function get_url:
 	* 
 	* @return 
 	*/	
	public function get_url()
	{
		return $this->get_attribute("DOC_EXTERN_URL");
	}

	/**
 	* function set_url:
 	* 
 	* @return 
 	*/	
	public function set_url( $pNewUrl )
	{
		return $this->set_attributes( array( "DOC_EXTERN_URL" => $pNewUrl ) );
	}

}

?>