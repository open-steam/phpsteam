<?php
namespace OpenSteam\Modules;

use steam_object,
    steam_connector,
    ParameterException;

class GroupsModule extends steam_object
{
    private $_steamObject;

    private static $instances = array();

    public static function getInstance($pSteamConnectorID)
    {
        if (!is_string($pSteamConnectorID)) throw new ParameterException( "pSteamConnectorID", "string" );
        if (isset(self::$instances[$pSteamConnectorID])) {
            return self::$instances[$pSteamConnectorID];
        } else {
            self::$instances[$pSteamConnectorID] = new self($pSteamConnectorID);
            return self::$instances[$pSteamConnectorID];
        }
    }

    public function __construct($pSteamConnectorID)
    {
        $groupsModule = steam_connector::get_instance($pSteamConnectorID)->get_module("groups");
        parent::__construct(steam_connector::get_instance($pSteamConnectorID), $groupsModule->get_id(), CLASS_MODULE);
        $this->_steamObject = $groupsModule;
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
