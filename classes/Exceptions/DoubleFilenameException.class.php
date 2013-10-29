<?php
class DoubleFilenameException extends Exception {

    public function __construct($name) {
        $this->message = "Double filename {$name}";

        parent::__construct( $this->message );
    }

    public function get_message() {
        return $this->message;
    }

}
