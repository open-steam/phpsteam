<?php
namespace OpenSteam\Helper;

use Exception;
use OpenSteam\Helper\LoggerHelper;
use PDO;
use PDOException;

class DatabaseHelper {
	private static $_instance;
	private $_pdo;

	private function __construct() {
		$this->_connect();
	}

	public static function getInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function _connect() {
		$db_host = STEAM_DATABASE_HOST;
		$db_database = STEAM_DATABASE;
		$db_user = STEAM_DATABASE_USER;
		$db_password = STEAM_DATABASE_PASS;
		if (!empty($db_host) && !empty($db_database) && !empty($db_user)) {
			$dsn = "mysql:dbname={$db_database};host={$db_host}";
			try {
				$this->_pdo = new PDO($dsn, $db_user, $db_password);
			} catch (PDOException $e) {
				LoggerHelper::getInstance()->getLogger()->error("failed to connect db", array("connection", $db_user . "@" . $db_host . ":/" . $db_database));
				throw new Exception("Unable to connect to database (connection: " . $db_user . "@" . $db_host . ":/" . $db_database, E_CONFIGURATION);
			}
		} else {
			throw new Exception("Unable to connect to database.", E_CONFIGURATION);
		}
	}

	private function disconnect() {
		//not needed
		//If you don't do this explicitly, PHP will automatically close the connection when your script ends.
		$this->_pdo = null;
	}

	public function getAllUsers() {
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
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function getObjCount() {
		$query = "SELECT COUNT(DISTINCT ob_id) AS cardinality FROM ob_class;";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->execute();
			$results = $statement->fetchAll();

			return $results[0][0];
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function getLastObjId() {
		$query = "SELECT MAX(ob_id) AS count FROM ob_class;";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->execute();
			$results = $statement->fetchAll();

			return $results[0][0];
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function getDocDataSize() {
		$query = "SELECT SUM(LENGTH(rec_data)) as size from doc_data;";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->execute();
			$results = $statement->fetchAll();

			return $results[0][0];
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function get_content_id($oid) {
		$query = "flush tables; select ob_data from ob_data where ob_attr='CONTENT_ID' AND ob_id=:oid";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->bindParam(':oid', $oid, PDO::PARAM_INT);
			$statement->execute();
			$results = $statement->fetchAll();

			return $results[0][0];
		} catch (PDOException $e) {
			$LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function get_content($cid) {
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
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function print_content($cid) {
		$query = "select rec_data from doc_data where doc_id=:cid order by rec_order";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->bindParam(':cid', $cid, PDO::PARAM_INT);
			$statement->execute();
			while ($arr = $statement->fetch()) {
				print $arr[0];
			}
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function set_content($cid, &$content) {
		$query = "delete from doc_data where doc_id = :content_id;";
		$query .= "insert into doc_data values(:content, :content_id, 1)";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->bindParam(':content_id', $content_id, PDO::PARAM_INT);
			$statement->bindParam(':content', $content, PDO::PARAM_STR);
			$statement->execute();
			$results = $statement->fetchAll();

			return $results;
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function download_and_print($oid) {
		$cid = $this->get_content_id($oid);

		if ($cid === -1) {
			LoggerHelper::getInstance()->getLogger()->error("download_handling::download_and_print " . $oid . " CONTENT_ID is -1 (not yet set in database).");
			exit;
		}
		$this->print_content($cid);
	}

	public function get_oid_by_path($path) {
		$query = "select ob_id from ob_data where ob_attr='OBJ_PATH' and ob_data=:path";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->bindParam(':path', $path, PDO::PARAM_STR);
			$statement->execute();
			$results = $statement->fetchAll();

			return $results[0][0];
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function get_attribute($id, $attribute) {
		$query = "select ob_data from ob_data where ob_id=:id and ob_attr=:attr";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->bindParam(':id', $id, PDO::PARAM_INT);
			$statement->bindParam(':attr', $attribute, PDO::PARAM_STR);
			$statement->execute();
			$results = $statement->fetchAll();

			if (isset($results[0][0])) {
				$result = $results[0][0];
				return $this->pikeDecode($result);
			} else {
				return 0;
			}
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function get_attributes($id, $attributes) {
		$attributesQuery = implode(',', array_fill(0, count($attributes), '?'));

		$query = "select ob_attr, ob_data from ob_data where ob_id=? and (ob_attr in (" . $attributesQuery . "))";

		array_unshift($attributes, $id);

		try {
			$statement = $this->_pdo->prepare($query);
			$statement->execute($attributes);
			$results = $statement->fetchAll();

			$resultData = array();

			foreach ($results as $result) {
				$resultData[$result['ob_attr']] = $this->pikeDecode($result['ob_data']);
			}

			foreach ($attributes as $attribute) {
				if (!isset($resultData[$attribute])) {
					$resultData[$attribute] = 0;
				}
			}

			return $resultData;

		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function find_object($id) {
		$query = "select ob_class from ob_class where ob_id=?";
		$statement = $this->_pdo->prepare($query);
		$statement->execute(array($id));
		$results = $statement->fetchAll();

		if (isset($results[0])) {
			$className = $results[0]['ob_class'];

			if ($className === '/classes/Object') {
				return new \steam_object($this, $GLOBALS['STEAM']->get_id(), $id);
			} else if ($className === '/classes/Document') {
				return new \steam_document($this, $GLOBALS['STEAM']->get_id(), $id);
			} else if ($className === '/classes/User') {
				return new \steam_user($this, $GLOBALS['STEAM']->get_id(), $id);
			}
		} else {
			return 0;
		}
	}

	public function pikeDecode($encoded) {
		if (strStartsWith($encoded, "%")) {
			return $this->find_object(substr($encoded, 1));
		}
		if (strStartsWith($encoded, "\"") && strEndsWith($encoded, "\"")) {
			return substr($encoded, 1, -1);
		}
		return $encoded;
	}

	public function download_and_print_path($path) {
		download_and_print($this->get_oid_by_path($path));
	}

	public function get_content_size($cid) {
		$query = "select length(rec_data) from doc_data where doc_id=:cid order by rec_order";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->bindParam(':cid', $cid, PDO::PARAM_INT);
			$statement->execute();

			$size = 0;
			while ($arr = $statement->fetch()) {
				$size += (int) $arr[0];
			}

			return $size;
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function get_user_oid($userName) {
		$query = "SELECT login, ob_id FROM i_userlookup WHERE login = :userName";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->bindParam(":userName", $userName, PDO::PARAM_STR);
			$statement->execute();
			$results = $statement->fetchAll();

			return (int) $results[0]['ob_id'];
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function get_users_mail_oids($uid) {
		$query = "SELECT ob_id,ob_ident,ob_data FROM ob_data WHERE ob_ident='annots' AND ob_id=:uid";
		try {
			$statement = $this->_pdo->prepare($query);
			$statement->bindParam(":uid", $uid, PDO::PARAM_INT);
			$statement->execute();
			$results = $statement->fetchAll();

			$decoded = $this->steam_decode($results[0]['ob_data']);

			return $decoded['Annotations'];
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function steam_decode($string) {
		$string = str_replace("[", "[[", $string);
		$string = str_replace("]", "]]", $string);
		$string = str_replace("{", "[", $string);
		$string = str_replace("}", "]", $string);
		$string = str_replace("[[", "{", $string);
		$string = str_replace("]]", "}", $string);

		$string = str_replace(",]", "]", $string);
		$string = str_replace(",}", "}", $string);

		$string = preg_replace("/(%\d+)/", "\"$1\"", $string);

		return json_decode($string, true);
	}

	public function getUnreadMails($userName) {
		$uid = $this->get_user_oid($userName);

		$mailOids = $this->get_users_mail_oids($uid);

		if (count($mailOids) === 1) {
			return 0;
		}

		$query = "SELECT k,v FROM i_read_documents WHERE ";

		foreach ($mailOids as $i => $mailOid) {
			$query .= "k = :mailOid{$i}";
			if ($i < count($mailOids) - 1) {
				$query .= " OR ";
			}
		}
		try {
			$statement = $this->_pdo->prepare($query);
			foreach ($mailOids as $i => $mailOid) {
				$mailOid = str_replace('%', '', $mailOid);
				$statement->bindValue(":mailOid{$i}", $mailOid, PDO::PARAM_STR);
			}
			$statement->execute();
			$results = $statement->fetchAll();

			$unreadMails = 0;
			foreach ($results as $key => $value) {
				if (!strpos($value['v'], (string) $uid)) {
					$unreadMails++;
				}
			}

			return $unreadMails;
		} catch (PDOException $e) {
			LoggerHelper::getInstance()->getLogger()->error('Connection failed: ' . $e->getMessage());
		}
	}

	public function countMails($userName) {
		$uid = $this->get_user_oid($userName);
		$mailOids = $this->get_users_mail_oids($uid);

		return (count($mailOids) - 1);
	}
}
