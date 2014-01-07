<?php
namespace OpenSteam\Search;

class SearchDefine
{
    public $limitSearch;
    public $extendSearch;
    public $fulltextSearch;

    public function init()
    {
        $this->limitSearch = array();
        $this->extendSearch = array();
        $this->fulltextSearch = array();
    }

    public function steamSearch()
    {
        $this->init();
    }

    public function eq($val)
    {
        if(is_numeric($val))

            return array( "=", (int) $val);
        else
            return array("=", "\"".$val."\"" );
    }

    public function uneq($val)
    {
        if(is_numeric($val))

            return array("!=", (int) $val);
        else
            return array("!=", "\"".$val."\"");
    }

    public function like($val)
    {
        return array("like", "\"".$val."\"");
    }

    public function search($store, $thekey, $value_operation)
    {
        $searching = array();
        $searching["storage"] 	= $store;
        $searching["key"]	 	= $thekey;
        $searching["value"]	 	= $value_operation;

        return $searching;
    }

    public function extendAttr($attr, $value)
    {
        $this->extendSearch[sizeof($this->extendSearch)] = $this->search("attrib", $attr, $value);
    }

    public function limitAttr($attr, $value)
    {
        $this->limitSearch[sizeof($this->limitSearch)] = $this->search("attrib", $attr, $value);
    }

    public function extendSearch($store, $thekey, $value)
    {
        $this->extendSearch[sizeof($this->extendSearch)] = $this->search($store, $thekey, $value);
    }

    public function limitSearch($store, $thekey, $value)
    {
        $this->limitSearch[sizeof($this->limitSearch)] = $this->search($store, $thekey, $value);
    }

    public function addFulltextSearch ($value)
    {
        $this->fulltextSearch[sizeof($this->fulltextSearch)] = array ("storage" => "doc_ft", "value" => $value);
    }

    public function getSearch()
    {
        return $this->extendSearch;
    }

    public function getExclusions()
    {
        return $this->limitSearch;
    }

    public function getFulltext ()
    {
        return $this->fulltextSearch;
    }
}
