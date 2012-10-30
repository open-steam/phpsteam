<?php

namespace OpenSteam\Persistence\ContentProvider;

class DatabaseContentProvider extends SteamContentProvider
{
    

    public function get_content(steam_document $document)
    {
        $identifier = $document->get_id();

        /*
        if (isset($_SESSION["LMS_USER"]) && $_SESSION["LMS_USER"] instanceof lms_user
            && $_SESSION["LMS_USER"]->is_logged_in()
        ) {
            $login = $_SESSION["LMS_USER"]->get_login();
            $password = $_SESSION["LMS_USER"]->get_password();
        } else {
            $login = 'guest';
            $password = 'guest';
        }
        try {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
        } catch (Exception $e) {

        }
        $downloader = new downloader();
        $downloader->connect_to_mysql();

        if (!$downloader->check_permissions($login, $identifier, $password)) {
            if ($login == 'guest') {
                throw new Exception("Access denied. Please login.", E_USER_AUTHORIZATION);
            }
            else {
                throw new Exception("No rights to download object " . $identifier . ".", E_USER_RIGHTS);
            }
        }
        $data = $downloader->get_document_attributes($identifier);
        $content = $downloader->get_content($identifier, $login);*/
        $downloader = new downloader();
        $downloader->connect_to_mysql();
        $content = $downloader->get_content($identifier);

        return $content;
    }
}