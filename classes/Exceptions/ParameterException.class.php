<?php
/**
 * ParameterException
 *
 * PHP versions 8.1
 *
 * @package     PHPsTeam
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Alexander Roth <aroth@it-roth.de>, Dominik Niehus <nicke@upb.de>
 * @copyright   2000-2022 Alexander Roth <aroth@it-roth.de>, Dominik Niehus <nicke@upb.de>
 */

/**
 *  @package     PHPsTeam
 */
class ParameterException extends Exception
{
	public $backtrace;
	public $parameter;
	public $type;
	public $message;

	/**
	 * constructor of ParameterException:
	 *
	 * @param $pParameter
	 * @param $pType
	 */
	public function __construct($pParameter = false, $pType = false)
	{
		if (!$pParameter) {
			$this->parameter = "[parameter not specified]";
		} else {
            $this->parameter = $pParameter;
        }
		if (!$pType) {
			$this->type = "[type not specified]";
		} else {
            $this->type = $pType;
        }

		$this->backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		
		$this->message = "Wrong parameter: '" . $this->parameter . "' is not of type '" . $this->type . "'";
		
		parent::__construct($this->message);
	}
	
	public function get_message() {
		return $this->message;
	}
	
	/**
	 * function get_backtrace:
	 *
	 * @return
	 */
	public function get_backtrace()
	{
		return var_export($this->backtrace, true);
	}
}