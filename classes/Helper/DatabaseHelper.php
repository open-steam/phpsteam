<?php

namespace OpenSteam\Helper;

use Exception,
    PDO,
    PDOException;

use Monolog\Registry;

class DatabaseHelper
{
    private static $_instance;
    private $_pdo;
    private $_logger;

    private function __construct()
    {
        $this->_logger = Registry::getInstance(API_LOGGER_CHANNEL);
        $this->_connect();
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function _connect()
    {
        $db_host = STEAM_DATABASE_HOST;
        $db_database = STEAM_DATABASE;
        $db_user = STEAM_DATABASE_USER;
        $db_password = STEAM_DATABASE_PASS;
        if (!empty($db_host) && !empty($db_database) && !empty($db_user)) {
            $dsn = "mysql:dbname={$db_database};host={$db_host}";
            try {
                $this->_pdo = new PDO($dsn, $db_user, $db_password);
            } catch (PDOException $e) {
                $this->_logger->error("failed to connect db", array("connection", $db_user . "@" . $db_host . ":/" . $db_database));
            }
        } else throw new Exception("Unable to connect to database.", E_CONFIGURATION);
        $this->_logger->debug("db connected", array("connection", $db_user . "@" . $db_host . ":/" . $db_database));
    }

    private function disconnect()
    {
        //not needed
        //If you don't do this explicitly, PHP will automatically close the connection when your script ends.
        $this->_pdo = null;
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM i_users;";
        try {
            $statement = $this->_pdo->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();

            $return_arr = array();

            foreach ($results as $result) {
                $result[1] = $result["v"] = substr($result[1], 1);
                $return_arr[$result["v"]] = $result["k"];
            }

            return $return_arr;
        } catch (PDOException $e) {
            $this->_logger->error('Connection failed: ' . $e->getMessage());
        }
    }

    public function getObjCount()
    {
        $query = "SELECT COUNT(DISTINCT ob_id) AS cardinality FROM ob_class;";
        try {
            $statement = $this->_pdo->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();

            return $results[0][0];
        } catch (PDOException $e) {
            $this->_logger->error('Connection failed: ' . $e->getMessage());
        }
    }

    public function getLastObjId()
    {
        $query = "SELECT MAX(ob_id) AS count FROM ob_class;";
        try {
            $statement = $this->_pdo->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();

            return $results[0][0];
        } catch (PDOException $e) {
            $this->_logger->error('Connection failed: ' . $e->getMessage());
        }
    }

    public function getDocDataSize()
    {
        $query = "SELECT SUM(LENGTH(rec_data)) as size from doc_data;";
        try {
            $statement = $this->_pdo->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();

            return $results[0][0];
        } catch (PDOException $e) {
            $this->_logger->error('Connection failed: ' . $e->getMessage());
        }
    }

    public function get_content_id($oid)
    {
        $query = "flush tables; select ob_data from ob_data where ob_attr='CONTENT_ID' AND ob_id=:oid";
        try {
            $statement = $this->_pdo->prepare($query);
            $statement->bindParam(':oid', $oid, PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll();

            return $results[0][0];
        } catch (PDOException $e) {
            $this->_logger->error('Connection failed: ' . $e->getMessage());
        }
    }

    public function get_content($cid)
    {
        $query = "select rec_data from doc_data where doc_id=:cid order by rec_order";
        try {
            $statement = $this->_pdo->prepare($query);
            $statement->bindParam(':cid', $cid, PDO::PARAM_INT);
            $statement->execute();
            $result = "";
            while ($arr = $statement->fetch()) {
                $result .= $arr[0];
            }

            return $result;
        } catch (PDOException $e) {
            $this->_logger->error('Connection failed: ' . $e->getMessage());
        }
    }

    public function print_content($cid)
    {
        $query = "select rec_data from doc_data where doc_id=:cid order by rec_order";
        try {
            $statement = $this->_pdo->prepare($query);
            $statement->bindParam(':cid', $cid, PDO::PARAM_INT);
            $statement->execute();
            while ($arr = $statement->fetch()) {
                print $arr[0];
            }
        } catch (PDOException $e) {
            $this->_logger->error('Connection failed: ' . $e->getMessage());
        }
    }

    public function set_content($cid, &$content)
    {
        $query = "delete from doc_data where doc_id = :content_id;";
        $query .= "insert into doc_data values(':content', :content_id, 1)";
        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':content_id', $content_id, PDO::PARAM_INT);
            $statement->bindParam(':content', $content, PDO::PARAM_STR);
            $statement->execute();
            $results = $statement->fetchAll();

            return $results;
        } catch (PDOException $e) {
            $this->_logger->error('Connection failed: ' . $e->getMessage());
        }
    }

    public function download_and_print($oid)
    {
        $cid = $this->get_content_id($oid);

        if ($cid === -1) {
            $this->_logger->error("download_handling::download_and_print " . $oid . " CONTENT_ID is -1 (not yet set in database).");
            exit;
        }
        $this->print_content($cid);
    }

    public function get_oid_by_path($path)
    {
        $query = "select ob_id from ob_data where ob_attr='OBJ_PATH' and ob_data=':path'";
        try {
            $statement = $this->_pdo->prepare($query);
            $statement->bindParam(':path', $path, PDO::PARAM_STR);
            $statement->execute();
            $results = $statement->fetchAll();

            return $results[0][0];
        } catch (PDOException $e) {
            $this->_logger->error('Connection failed: ' . $e->getMessage());
        }
    }

    public function download_and_print_path($path)
    {
        download_and_print($this->get_oid_by_path($path));
    }

    public function get_content_size($contentid)
    {
        $size = 0;
        $query = "select length(rec_data) from doc_data where doc_id=" . $contentid . " order by rec_order";
        $result = mysql_query($query);
        $i = 0;
        while ($row = mysql_fetch_row($result)) {
            $i++;
            $size += (int) $row[0];
        }

        return $size;
    }

        public function getUnreadMails($userName="")
    {
        //$link = mysql_connect(STEAM_DATABASE_HOST, STEAM_DATABASE_USER, STEAM_DATABASE_PASS, true);
        if (!$link) {
            API_DEBUG ? $GLOBALS["MONOLOG"]->addError('no connection: ' . mysql_error()) : "";
            die("Probleme mit der Datenbank. Wir arbeiten an einer L&ouml;sung.");
        }

        mysql_select_db(STEAM_DATABASE, $link);
        $result = mysql_query("SELECT login, ob_id FROM i_userlookup WHERE login='".$userName."'");

        $userObjectId = 0;
        while ($row = @mysql_fetch_array($result)) {
            $userObjectId = $row["ob_id"];
        }

        if ($userObjectId==0) {
            return 0;
        }

        //secound query
        $mailObjectIdsString = "";
        $result = mysql_query("SELECT ob_id,ob_ident,ob_data FROM ob_data WHERE ob_ident='annots' AND ob_id='".$userObjectId."'");
        while ($row = mysql_fetch_array($result)) {
            $mailObjectIdsString = $row["ob_data"];
        }

        //find the object numbers
        //extract object numbers from the string
        //these are the object ids of the mails
        $mailObjectIdsStringCut = $mailObjectIdsString;
        $objectNumbers = array();
        while (true) {
            $firstPercent = stripos($mailObjectIdsStringCut, "%");
            $firstKomma = stripos($mailObjectIdsStringCut, ",");
            if ($firstPercent === false) break;
            $objectNumbers[] = substr($mailObjectIdsStringCut, $firstPercent + 1, $firstKomma - $firstPercent - 1);
            $mailObjectIdsStringCut = substr($mailObjectIdsStringCut, $firstKomma + 1);
        }

        //build up a long sql string
        $mailsCount=0;
        $first = true;
        foreach ($objectNumbers as $mailObjectNumber) {
            $mailsCount++;
            if ($first) {
                $first=false;
                $allMailsQuery = "SELECT k,v FROM i_read_documents WHERE k='".$mailObjectNumber."'";
            } else {
                $allMailsQuery.=" OR k='".$mailObjectNumber."'";
            }
        }
        $mailsCount--;

        //check if mails are read
        $allMailsData = array();
        $result = mysql_query($allMailsQuery);
        while ($row = mysql_fetch_array($result)) {
            $allMailsData[] = $row;
        }

        $readMailsCount=0;
        if (isset($allMailsData)) {
            foreach ($allMailsData as $mailData) {
                if (stripos($mailData["v"], $userObjectId) != false) {
                    $readMailsCount++;
                }
            }
        }

        mysql_close($link);
        $unreadMails = $mailsCount-$readMailsCount;

        return $unreadMails;
    }

    public function countMails($userName = "")
    {
       // $link = mysql_connect(STEAM_DATABASE_HOST, STEAM_DATABASE_USER, STEAM_DATABASE_PASS, true);
        if (!$link) {
            error_log('no connection: ' . mysql_error());
            die("Probleme mit der Datenbank. Wir arbeiten an einer L&ouml;sung.");
        }

        mysql_select_db(STEAM_DATABASE, $link);
        $result = mysql_query("SELECT login, ob_id FROM i_userlookup WHERE login='".$userName."'");

        $userObjectId = 0;
        while ($row = @mysql_fetch_array($result)) {
            $userObjectId = $row["ob_id"];
        }

        if ($userObjectId==0) {
            return 0;
        }

        //secound query
        $mailObjectIdsString = "";
        $result = mysql_query("SELECT ob_id,ob_ident,ob_data FROM ob_data WHERE ob_ident='annots' AND ob_id='".$userObjectId."'");
        while ($row = mysql_fetch_array($result)) {
            $mailObjectIdsString = $row["ob_data"];
        }

        //find the object numbers
        //extract object numbers from the string
        //these are the object ids of the mails
        $mailObjectIdsStringCut = $mailObjectIdsString;
        $objectNumbers = array();
        while (true) {
            $firstPercent = stripos($mailObjectIdsStringCut,"%");
            $firstKomma = stripos($mailObjectIdsStringCut,",");
            if ($firstPercent===FALSE) break;
            $objectNumbers[]=substr($mailObjectIdsStringCut,$firstPercent+1,$firstKomma-$firstPercent-1);
            $mailObjectIdsStringCut = substr($mailObjectIdsStringCut,$firstKomma+1);
        }

        return (count($objectNumbers) - 1);
    }
}
