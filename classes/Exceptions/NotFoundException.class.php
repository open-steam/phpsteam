<?php
class NotFoundException extends steam_exception {

    public function __construct($login) {
        parent::__construct($login, "steam object not found", COAL_E_NOTEXIST);
    }
}
