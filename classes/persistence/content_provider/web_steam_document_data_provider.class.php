<?php

class WebSteamDocumentDataProvider extends  SteamDocumentDataProvider {

    public $password, $login;

    public function __construct(){
        if (isset($_SESSION["LMS_USER"]) && $_SESSION["LMS_USER"] instanceof lms_user && $_SESSION["LMS_USER"]->is_logged_in()) {
            $this->login = $_SESSION["LMS_USER"]->get_login();
            $this->password = $_SESSION["LMS_USER"]->get_password();
        } else {
            $this->login = 'guest';
            $this->password = 'guest';
        }

        $this->steam_connector = steam_connector::connect(STEAM_SERVER, STEAM_PORT, $this->login, $this->password);
    }

    public function get_content(steam_document $document) {
        $https_port = (int) $this->steam_connector->get_config_value("https_port");
        if ($https_port == 443 || $https_port == 0)
            $https_port = "";
        else
            $https_port = ":" . (string) $https_port;
        $ch = curl_init("https://" . STEAM_SERVER . $https_port . "/scripts/get.pike?object=" . $document->get_id());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_USERPWD, $this->login . ":" . $this->password);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}