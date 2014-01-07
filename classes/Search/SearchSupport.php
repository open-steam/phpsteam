<?php
namespace OpenSteam\Search;

class SearchSupport
{
    private $_steamObject;

    public function __construct($steam_object)
    {
        $this->_steamObject = $steam_object;
    }

    public function searchUserPosts($message_board, $user)
    {
        return $this->_steamObject->get_steam_connector()->predefined_command(
            $this->_steamObject,
            "search_user_posts",
            array($message_board, $user),
            0
        );
    }

    public function searchMessageboard($message_board, $pattern)
    {
        return $this->_steamObject->get_steam_connector()->predefined_command(
            $this->_steamObject,
            "search_messageboard",
            array($message_board, $pattern),
            0
        );
    }
}
