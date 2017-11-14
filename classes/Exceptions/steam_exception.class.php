<?php
/**
 * steam_exception
 *
 * PHP versions 5
 *
 * @package     PHPsTeam
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Alexander Roth <aroth@it-roth.de>, Daniel Büse <dbuese@upb.de>, Dominik Niehus <nicke@upb.de>
 */

/**
 *
 * @package     PHPsTeam
 */
class steam_exception extends Exception {
	public $backtrace;
	public $user;
	public $allow_backtrace;
	private $security_issue = "Backtrace of this error is not available due to security issues.";

	/**
	 * constructor of steam_exception:
	 *
	 * @param $pUser
	 * @param $pMessage
	 * @param $pCode
	 */
	public function __construct($pUser = "Anonymous", $pMessage = FALSE, $pCode = FALSE, $pallow_backtrace = TRUE) {
		if (!$pMessage) {
			$this->message = "non-specified error";
		}
		if (!$pCode) {
			$this->code = 0;
		}
		$this->user = $pUser;
		$this->allow_backtrace = $pallow_backtrace;
		if ($pallow_backtrace) {
			$this->backtrace = $this->debug_string_backtrace();
		} else {
			$this->backtrace = $this->security_issue;
		}
		parent::__construct($pMessage, $pCode);
	}

	/**
	 * function get_backtrace:
	 *
	 * @return
	 */
	public function get_backtrace() {
		return $this->backtrace;
	}

	private function debug_string_backtrace() {
        ob_start();
        debug_print_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 10);
        $trace = ob_get_contents();
        ob_end_clean();

        // Remove first item from backtrace as it's this function which
        // is redundant.
        $trace = preg_replace('/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1);

        // Renumber backtrace items.
        //$trace = preg_replace ('/^#(\d+)/me', '\'#\' . ($1 - 1)', $trace);
        $trace = preg_replace_callback('/^#(\d+)/m', function($matches){
        	foreach($matches as $match){
	            return '#' . ($match - 1);
	        }
        }, $trace);

        return $trace;
    }

	/**
	 * function get_message:
	 *
	 * @return
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * function get_code:
	 *
	 * @return
	 */
	public function get_code() {
		return $this->code;
	}

	/**
	 * function get_user:
	 *
	 * @return
	 */
	public function get_user() {
		return $this->user;
	}

	/**
	 * override super method  to get control of log output if exception is not
	 * catched
	 */
	function __toString() {
		if ($this->allow_backtrace) {
			return $this->get_message() . "\n" . $this->get_backtrace();
		} else {
			return $this->security_issue;
		}
	}
}