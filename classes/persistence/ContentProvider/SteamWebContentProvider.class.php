<?php

namespace OpenSteam\Persistence\ContentProvider;

class SteamWebContentProvider extends  SteamContentProvider {

   public function getContent(\steam_document $document, $buffer = 0) {
        $https_port = (int) $document->get_steam_connector()->get_config_value("https_port");
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