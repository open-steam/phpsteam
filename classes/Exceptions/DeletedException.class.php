<?php
class DeletedException extends steam_exception {

    public function __construct($login) {
        parent::__construct($login, "steam object deleted", COAL_E_DELETED);
    }
}
