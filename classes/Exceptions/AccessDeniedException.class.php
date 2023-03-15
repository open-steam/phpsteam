<?php
class AccessDeniedException extends steam_exception {

    public function __construct($login) {
        parent::__construct($login, "access denied (" . $login . ")", COAL_E_NOTEXIST);
    }
}
