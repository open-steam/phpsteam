<?php
class DoubleFilenameException extends Exception {

    public function __construct() {
        $this->message = "Double filename";

        parent::__construct( $this->message );
    }

    public function get_message() {
        return $this->message;
    }

}
