<?php
class ContentSizeException extends Exception {

    public function __construct() {
        $this->message = "file is too big";

        parent::__construct( $this->message );
    }

    public function get_message() {
        return $this->message;
    }

}
