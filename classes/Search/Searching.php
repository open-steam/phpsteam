<?php
namespace OpenSteam\Search;

use steam_request;

class Searching
{
    private $_steamObject;

    public function __construct($steam_object)
    {
        $this->_steamObject = $steam_object;
    }

    /**
     * Searches for objects which fit the options defined by $search_parameters
     *
     * Exclusions may not work - I never got it working myself.
     *
     * For searching bulletinboards please use the searchsupport.class.php
     *
     * Don't search for OBJ_NAME, OBJ_DESC and OBJ_KEYWORDS together, make two searches, one
     * for OBJ_NAME, OBJ_DESC and one for OBJ_KEYWORDS
     *
     * @param $search_parameters searches created by the search_define-class
     * @param $class_type        class of search result
     *
     * @return an array of objects which are the result of the search
     */
    public function search($search_parameters, $class_type = 0)
    {
        $searches = $search_parameters->getSearch();
        $exlusions = $search_parameters->getExclusions();
        $fulltext = $search_parameters->getFulltext();

        $searches = ($searches == null ? array() : $searches);
        $exlusions = ($exlusions == null ? array() : $exlusions);
        $fulltext = ($fulltext == null ? array() : $fulltext);

        $myrequest = new steam_request(
                                        $this->_steamObject->get_steam_connector()->get_id(),
                                        $this->_steamObject->get_steam_connector()->get_transaction_id(),
                                        $this->_steamObject,
                                        array("searchAsync", array(
                                                                    $searches,
                                                                    $exlusions,
                                                                    $fulltext,
                                                                    $class_type)),
                                                            COAL_COMMAND);

        $answer = $this->steam_object->get_steam_connector()->command($myrequest);

        return $answer->get_arguments();
    }
}
