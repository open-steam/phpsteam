<?php
class TooManyFilesPerContainerException extends Exception {

    public function __construct() {
        $this->message = "too many file in container";

        parent::__construct( $this->message );
    }

    public function get_message() {
        return $this->message;
    }

}
