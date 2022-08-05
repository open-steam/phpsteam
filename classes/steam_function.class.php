<?php

/**
 * steam_function
 *
 * PHP versions 8.1
 *
 * @package     PHPsTeam
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Daniel Büse <dbuese@upb.de>, Domink Niehus <nicke@upb.de>
 * @copyright   2000-2022 Daniel Büse <dbuese@upb.de>, Domink Niehus <nicke@upb.de>
 *
 */

/**
 *
 * @package     PHPsTeam
 */
class steam_function
{
	protected $function_name;

	/**
	 * constructor of steam_function:
	 *
	 * @param $pSteamConnector
	 * @param $pID
	 */
	public function __construct( $pFunctionName )
	{
		$this->function_name = $pFunctionName;
	}

	function get_function_name() {
		return $this->function_name;
	}

}