<?php

namespace OpenSteam\Persistence\ContentProvider;

class SteamWebContentProvider extends  SteamContentProvider
{
    public function getContent(\steam_document $document, $buffer = 0)
    {
        $callback = function(\steam_document $document) {
            $https_port = (int) $document->get_steam_connector()->get_config_value("https_port");
            if ($https_port == 443 || $https_port == 0)
                $https_port = "";
            else
                $https_port = ":" . (string) $https_port;
            $ch = curl_init("https://" . STEAM_SERVER . $https_port . "/scripts/get.pike?object=" . $document->get_id());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($ch, CURLOPT_USERPWD, \steam_connection::get_instance($document->get_steam_connector()->get_id())->get_login_user_name() . ":" . \steam_connection::get_instance($document->get_steam_connector()->get_id())->get_login_passwd());
            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        };

        $module_read_doc = $document->get_steam_connector()->get_module("table:read-documents");
        $document->steam_command($module_read_doc, "download_document", array(8, $document), 0);

        if ($buffer) {
            $tid = $document->get_steam_connector()->add_to_buffer($document);
            $document->get_steam_connector()->add_buffer_result_callback($tid, $callback);

            return $tid;
        } else {
            return $callback($document);
        }
    }

    public function printContent(\steam_document $document)
    {
            $module_read_doc = $document->get_steam_connector()->get_module("table:read-documents");
            $document->steam_command($module_read_doc, "download_document", array(8, $document), 0);

             $https_port = (int) $document->get_steam_connector()->get_config_value("https_port");
             if ($https_port == 443 || $https_port == 0)
                 $https_port = "";
             else
                 $https_port = ":" . (string) $https_port;
             $ch = curl_init("https://" . STEAM_SERVER . $https_port . "/scripts/get.pike?object=" . $document->get_id());
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
             curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
             curl_setopt($ch, CURLOPT_USERPWD, \steam_connection::get_instance($document->get_steam_connector()->get_id())->get_login_user_name() . ":" . \steam_connection::get_instance($document->get_steam_connector()->get_id())->get_login_passwd());
             print curl_exec($ch);
             curl_close($ch);
    }
}
