<?php

class PersistenceFactory {

    static private $instance = null;

    /**
     *
     * @return PersistenceFactory 
     */
    static public function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {

    }

    public function create_file_persistence($persistence_type) {
        if ($persistence_type === PERSISTENCE_FILESYSTEM_RANDOM) {
            return new RandomFilePersistence();
        } else if ($persistence_type === PERSISTENCE_FILESYSTEM_HASH) {
            return new HashFilePersistence();
        } else {
            return new SteamPersistence();
        }
    }

}