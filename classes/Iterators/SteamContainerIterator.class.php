<?php

namespace OpenSteam\Iterators;

use RecursiveIterator,
    steam_connector,
    steam_factory,
    steam_container;

class SteamContainerIterator implements RecursiveIterator {

    protected $key = 0;
    protected $childObjs = array();

    private static $homeID;

    public function __construct($steam_container) {
        if (!self::$homeID) {
            self::$homeID = steam_factory::get_object_by_name($steam_container->steam_connectorID, "/home")->get_id();
        }
        if ($steam_container instanceof steam_container) {
            if ($steam_container->get_id() === self::$homeID) {
                $dbHelper = \OpenSteam\Helper\DatabaseHelper::getInstance();
                $users = $dbHelper->getAllUsers();
                sort($users);
                $homes = array();
                foreach ($users as $i => $user) {
                    $user = steam_factory::get_user($steam_container->steam_connectorID, $user);
                    $homes[] = $user->get_attribute(USER_WORKROOM);
                }

                $groups_module = steam_connector::get_instance($steam_container->steam_connectorID)->get_module("groups");
                $groups = steam_connector::get_instance($steam_container->steam_connectorID)->predefined_command($groups_module, "get_groups", array(), false);
                foreach ($groups as $i => $group) {
                    $homes[] = $group->get_attribute(GROUP_WORKROOM);
                }
                $this->childObjs = $homes;
            } else {
                $this->childObjs = $steam_container->get_inventory();
            }
            $GLOBALS["MONOLOG"]->addDebug("\t".count($this->childObjs));
        }

        $GLOBALS["MONOLOG"]->addDebug("\tConstruct SteamContainerIterator successfull");
    }

    public function current() {
        return $this->childObjs[$this->key];
    }

    public function getChildren() {
        return new SteamContainerIterator($this->current());
    }

    public function hasChildren() {
        $current = $this->current();
        if ($current instanceof steam_container) {
            $count = $current->count_inventory();
            $GLOBALS["MONOLOG"]->addDebug("\tCount children: " . $count . " Obj:" . $current->get_id());

            if ($count > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function key() {
        return $this->key;
    }

    public function next() {
        $this->key++;
    }

    public function rewind() {
        $this->key = 0;
    }

    public function valid() {
        return isset($this->childObjs[$this->key]);
    }
}