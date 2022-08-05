<?php

/**
 * steam_script
 *
 * PHP versions 8.1
 *
 * @package     PHPsTeam
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Daniel Büse <dbuese@upb.de>, Dominik Niehus <nicke@upb.de>
 * @copyright   2000-2022 Daniel Büse <dbuese@upb.de>, Dominik Niehus <nicke@upb.de>
 *
 */

/**
 *
 * @package     PHPsTeam
 */
class steam_script extends steam_object
{
	public function get_type() {
		return CLASS_SCRIPT | CLASS_OBJECT;
	}
}