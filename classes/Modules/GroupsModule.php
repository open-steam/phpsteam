<?php
namespace OpenSteam\Modules;
class GroupsModule extends steam_object
{
    private $_steamObject;

    public function __construct($steam_object)
    {
        parent::__construct($steam_object->get_steam_connector(), $steam_object->get_id(), CLASS_MODULE);
        $this->_steamObject = $steam_object;
    }

    public function getTopGroups($pBuffer = false)
    {
        return $this->steam_object->get_steam_connector()->predefined_command(
            $this->steam_object,
            "get_top_groups",
            array( ),
            $pBuffer
        );
    }
}
