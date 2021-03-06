<?php
namespace OpenSteam\Modules;

use steam_object,
    steam_connector,
    steam_factory,
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
        parent::__construct(steam_factory::get_instance(), steam_connector::get_instance($pSteamConnectorID), $groupsModule->get_id(), CLASS_MODULE);
        $this->_steamObject = $groupsModule;
    }

    public function getTopGroups($pBuffer = false)
    {
        return $this->_steamObject->get_steam_connector()->predefined_command(
            $this->_steamObject,
            "get_top_groups",
            array( ),
            $pBuffer
        );
    }
}
