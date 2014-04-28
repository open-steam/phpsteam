<?php
class NotFoundException extends steam_exception {

    public function __construct() {
        $this->message = "steam object not found";

        parent::__construct( $this->message );
    }

    public function get_message() {
        return $this->message;
    }

}
