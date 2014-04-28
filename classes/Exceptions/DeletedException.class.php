<?php
class DeletedException extends steam_exception {

    public function __construct() {
        $this->message = "steam object deleted";

        parent::__construct( $this->message );
    }

    public function get_message() {
        return $this->message;
    }

}
