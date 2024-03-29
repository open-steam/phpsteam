<?php
/**
 * Implements the steam_wiki class
 *
 * Longer description follows
 *
 * PHP versions 8.1
 * @package     PHPsTeam
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Alexander Roth <aroth@it-roth.de>, Dominik Niehus <nicke@upb.de>
 * @copyright   2000-2022 Alexander Roth <aroth@it-roth.de>, Dominik Niehus <nicke@upb.de>
 */

/**
 *
 * @package     PHPsTeam
 */
class steam_wiki extends steam_document
{
	public function get_type() {
		return CLASS_WIKI | CLASS_DOCUMENT | CLASS_OBJECT;
	}
}