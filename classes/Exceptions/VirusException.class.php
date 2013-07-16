<?php
class VirusException extends Exception {

    public function __construct() {
        $this->message = "file contains a virus";

        parent::__construct( $this->message );
    }

    public function get_message() {
        return $this->message;
    }

}