<?php
/**
 * Implements the steam_document class
 *
 * Longer description follows
 *
 * PHP versions 8.1
 *
 * @package     PHPsTeam
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Alexander Roth <aroth@it-roth.de>, Dominik Niehus <nicke@upb.de>
 * @copyright   2000-2022 Alexander Roth <aroth@it-roth.de>, Dominik Niehus <nicke@upb.de>
 */

/**
 * steam_document
 *
 * Longer description follows
 *
 * @package PHPsTeam
 */

class steam_document extends steam_object {
	private $_persistence;

	private static $contentCache = [];

	public static function getPersistenceById($id) {
		if (!ENABLE_FILE_PERSISTENCE && $id !== 0) {
			throw new Exception("file persistence disabled but expected!");
		}
		if (ENABLE_FILE_PERSISTENCE && $id === PERSISTENCE_FILE_UID) {
			$result = \OpenSteam\Persistence\FileUidPersistence::getInstance();
		} elseif (ENABLE_FILE_PERSISTENCE && $id === PERSISTENCE_FILE_CONTENTID) {
			$result = \OpenSteam\Persistence\FileContentIdPersistence::getInstance();
		} elseif ($id === PERSISTENCE_DATABASE) {
			$result = \OpenSteam\Persistence\DatabasePersistence::getInstance();
		} else {
			$result = \OpenSteam\Persistence\DatabasePersistence::getInstance();
		}

		return $result;
	}

	public function getPersistenceType() {
		$id = $this->get_attribute(DOC_PERSISTENCE_TYPE);
		if (ENABLE_FILE_PERSISTENCE && $id === PERSISTENCE_FILE_UID) {
			return $id;
		} elseif (ENABLE_FILE_PERSISTENCE && $id === PERSISTENCE_FILE_CONTENTID) {
			return $id;
		} elseif ($id === PERSISTENCE_DATABASE) {
			return $id;
		} else {
			return PERSISTENCE_DATABASE;
		}
	}

	public function getPersistence() {
		if (!isset($this->_persistence)) {
			$docPersistenceType = $this->get_attribute(DOC_PERSISTENCE_TYPE);
			$this->_persistence = self::getPersistenceById($docPersistenceType);
		}

		return $this->_persistence;
	}

	public function migratePersistence($toPersistenceType, $migrateVerions = true) {
		//new persistence
		$newPersistence = self::getPersistenceById($toPersistenceType);

		if ($newPersistence === $this->_persistence) {
			return 0;
		}

		if (!$newPersistence->allowed($this)) {
			return -1;
		}

		//get content
		$content = $this->get_content();

		//change persistence without creating a new document version
		$newPersistence->migrateSave($this, $content);

		//change persistence type
		$this->unlock_attribute(DOC_PERSISTENCE_TYPE);
		$this->set_attribute(DOC_PERSISTENCE_TYPE, $toPersistenceType);
		$this->lock_attribute(DOC_PERSISTENCE_TYPE);
		$this->_persistence = $newPersistence;

		//migrate all versions
		if ($migrateVerions) {
			$versions = $this->get_attribute(DOC_VERSIONS);
			foreach ($versions as $i => $version) {
				$version->migratePersistence($toPersistenceType, false);
			}
		}

		return 1;
	}

	public function get_type() {
		return CLASS_DOCUMENT | CLASS_OBJECT;
	}

	/**
	 *function download:
	 *
	 * @return success or not
	 */
	public function download($type = DOWNLOAD_ATTACHMENT, $params = array()) {
		if ($type === DOWNLOAD_ATTACHMENT) {
			$downloaderClass = "\\OpenSteam\\Persistence\\Downloader\\AttachmentDownloader";
		} elseif ($type === DOWNLOAD_IMAGE) {
			$downloaderClass = "\\OpenSteam\\Persistence\\Downloader\\ImageDownloader";
		} elseif ($type === DOWNLOAD_INLINE) {
			$downloaderClass = "\\OpenSteam\\Persistence\\Downloader\\InlineDownloader";
		} elseif ($type === DOWNLOAD_RANGE) {
			$downloaderClass = "\\OpenSteam\\Persistence\\Downloader\\RangeDownloader";
		}
		array_unshift($params, $this);

		return call_user_func_array(array($downloaderClass, "download"), $params);
	}

	/**
	 * function get_readers:
	 *
	 * @param $pBuffer
	 *
	 * @return
	 */
	public function get_readers($pBuffer = 0) {
		$module_read_doc = $this->get_steam_connector()->get_module("table:read-documents");

		return $this->steam_command(
			$module_read_doc,
			"get_readers",
			array($this),
			$pBuffer
		);
	}

	/**
	 * function is_read:
	 *
	 * @param $pUser
	 * @param $pBuffer
	 *
	 * @return
	 */
	public function is_reader($pUser = "", $pBuffer = 0) {
		$pUser = (empty($pUser)) ? $this->get_steam_connector()->get_current_steam_user() : $pUser;
		$module_read_doc = $this->get_steam_connector()->get_module("table:read-documents");

		return $this->steam_command(
			$module_read_doc,
			"is_reader",
			array($this, $pUser),
			$pBuffer
		);
	}

	/**
	 * function set_content:
	 *
	 * Sets the content of this document
	 * @param  string  $pContent document's content
	 * @param  Boolean $pBuffer  send now or buffer request?
	 * @return int     content size
	 */
	public function set_content($pContent, $pBuffer = 0, $noVersion = false) {
		if (!$this->check_access_write()) {
			throw new steam_exception($this->get_steam_connector()->get_login_name(), 'Access denied for user', 120, false);
		}
		$tmpfile = tempnam(API_TEMP_DIR, "API");
		if (is_resource($pContent)) {
			file_put_contents($tmpfile, $pContent);
			if (filesize($tmpfile) > API_MAX_CONTENT_SIZE) {
				throw new ContentSizeException();
			}
		} elseif (is_string($pContent)) {
			if (strlen($pContent) > API_MAX_CONTENT_SIZE) {
				throw new ContentSizeException();
			}
			file_put_contents($tmpfile, $pContent);
		} else {
			//throw new ParameterException("pContent", "resource or string");
			file_put_contents($tmpfile, "");
		}

		self::virusScan($tmpfile);

		$handle = fopen($tmpfile, 'r');

		$result = $this->getPersistence()->save($this, $handle, $pBuffer, $noVersion);
		//if ($noVersion) {
		//    $this->set_attribute("DOC_LAST_MODIFIED", time());
		//    $this->set_attribute("DOC_USER_MODIFIED", ...);
		//}
		unset($this->attributes[OBJ_VERSIONOF]);
		unset($this->attributes[DOC_VERSION]);
		unset($this->attributes[DOC_VERSIONS]);
		unset($this->attributes[DOC_LAST_MODIFIED]);
		unset($this->attributes[DOC_USER_MODIFIED]);
		unset($this->attributes[DOC_SIZE]);
		unlink($tmpfile);

		// Remove Versions
        if ($this->get_attribute('DOC_AUTO_DELETE_VERSIONS') === 1) {
            $docVersions = $this->get_attribute('DOC_VERSIONS');
            if (is_array($docVersions)) {
                if (count($docVersions) < 37) {
                    $docMaxKeepVersions = $this->get_attribute('DOC_MAX_KEEP_VERSIONS');
                    if (count($docVersions) > $docMaxKeepVersions) {
                        ksort($docVersions);
                        $countDeleteVersions = count($docVersions) - $docMaxKeepVersions;
                        $i = 0;
                        foreach ($docVersions as $versionNumber => $version) {
                            if ($i <= $countDeleteVersions) {
								if (isset($version) && $version instanceof steam_object) {
									$version->set_attribute("OBJ_VERSIONOF", 0);
									$version->set_attribute("DOC_VERSIONS", new \stdClass()	);
									$version->delete();
								}
                                unset($docVersions[$versionNumber]);
                                error_log("deleted version " . $versionNumber);
                            } else {
                                break;
                            }
                            $i++;
                        }
                        $this->set_attribute('DOC_VERSIONS', $docVersions);
                    }
                } else {
                    error_log("too many versions");
                }
            } else {
                error_log("no versions");
            }
        }


        self::$contentCache[$this->get_id()] = $pContent;
		return $result;
	}

	public static function virusScan($filename) {
		if (API_VIRUS_SCAN) {
			$class = DEFAULT_VIRUS_SCAN;
			$scanner = new $class;
			$clean_file = $scanner->scanFile($filename);
			if (!$clean_file) {
				throw new VirusException();
			}
		}
	}

	/**
	 * function get_content_size:
	 *
	 * This function returns the content size in Byte
	 *Example:
	 *<code>
	 *$size = $myDocument->get_content_size()
	 *</code>
	 *
	 * @param Boolean $pBuffer send now or buffer request?
	 *
	 * @return Integer the content size in Byte
	 */
	public function get_content_size($pBuffer = 0) {
		return $this->getPersistence()->getSize($this, $pBuffer);
	}

	/**
	 * function get_content_size:
	 *
	 * This function returns the content id
	 *Example:
	 *<code>
	 *$id = $myDocument->get_content_id()
	 *</code>
	 *
	 * @param Boolean $pBuffer send now or buffer request?
	 *
	 * @return Integer the content id
	 */
	public function get_content_id($pBuffer = 0) {
		$result = $this->steam_command(
			$this,
			"get_content_id",
			array(),
			$pBuffer
		);
		if ($pBuffer == 0) {
			$this->attributes["DOC_ID"] = $result;
		}

		return $result;
	}

	/**
	 * function get_content:
	 * This function returns the content of the document
	 *
	 *Example:
	 *<code>
	 *$content = myDocument->get_content()
	 *</code>
	 * @param Boolean $pBuffer send now or buffer request?
	 *
	 * @return String content of the document
	 *
	 */
	public function get_content($pBuffer = 0) {
		if (!$this->check_access_read()) {
			throw new steam_exception($this->get_steam_connector()->get_login_name(), 'Access denied for user', 120, false);
		}
		if (!isset(self::$contentCache[$this->get_id()])) {
            self::$contentCache[$this->get_id()] = $this->getPersistence()->load($this, $pBuffer);
		}
		return self::$contentCache[$this->get_id()];
	}

	public function print_content() {
		if (!$this->check_access_read()) {
			throw new steam_exception($this->get_steam_connector()->get_login_name(), 'Access denied for user', 120, false);
		}
		return $this->getPersistence()->printContent($this);
	}

	public function delete_all_versions($pBuffer = 0) {
		$parent = $this->get_attribute(OBJ_VERSIONOF);
		if (!$parent instanceof steam_document) {
			$versions = $this->get_attribute(DOC_VERSIONS);
			$count = sizeof($versions);
			if (!empty($versions)) {
				foreach ($versions as $i => $version) {
					if (!($version instanceof steam_document)) {
						continue;
					}
					$version->delete(false, $pBuffer);
				}
				$this->set_attribute("DOC_VERSIONS", new stdClass(), $pBuffer);
				$this->set_attribute("DOC_VERSION", 1, $pBuffer);
			}

			return $count;
		} else {
			return false;
		}
	}

	public function delete($handleVersions = true, $pBuffer = 0) {
		if ($handleVersions) {
			$parent = $this->get_attribute(OBJ_VERSIONOF);
			if ($parent instanceof steam_document) {
				//is version?
				$all_versions = $parent->get_attribute(DOC_VERSIONS);
				$keys = array_keys($all_versions);
				sort($keys);
				$new_array = array();
				$new_key = 1;
				foreach ($keys as $key) {
					// renumber other versions
					$version = $all_versions[$key];
					if (!($version instanceof steam_document) || $version->get_id() == $this->get_id()) {
						continue;
					}
					$version->set_attribute("DOC_VERSION", $new_key);
					$new_array[$new_key] = $version;
					$new_key++;
				}

				if (empty($new_array)) {
					$parent->set_attribute("DOC_VERSIONS", new stdClass());
					$parent->set_attribute("DOC_VERSION", 1);
				} else {
					$parent->set_attribute("DOC_VERSIONS", $new_array);
					$parent->set_attribute("DOC_VERSION", count($new_array) + 1);
				}
			} else {
				//delete versions if not is version
				$versions = $this->get_previous_versions();
				foreach ($versions as $version) {
					if ($version instanceof steam_document) {
						$version->delete(false);
					}
				}
			}
		}

		$this->getPersistence()->delete($this, $pBuffer);

		return parent::delete($pBuffer);
	}

	public function low_copy() {
		$copy = parent::low_copy();

		$this->getPersistence()->low_copy($this, $copy);

		return $copy;
	}

	/**
	 * get wiki content as html
	 *
	 * Please make sure your document is of type wiki (mime type ="text/wiki"
	 * before calling this method.
	 *
	 * please note that you must replace the links within the wiki by hand
	 * because the pathes in the replace have to fit you applications pathes
	 * The Link Terms are:
	 * "/scripts/wikiedit.pike?path=&lt;internal path in steam&gt;"- links to a non existing wiki
	 * "&lt;internal path in steam&gt;" - links to another wiki
	 * You have to replace these links into some path your application is able
	 * to handle and process the "create wiki" e.g. the "show wiki" commands
	 *
	 * @param $pBuffer TRUE if buffer this command
	 * @return html representation of the wikis content
	 */
	public function get_content_html($pBuffer = FALSE) {
		$wikimodule = $this->get_steam_connector()->get_module("wiki");
		if (is_object($wikimodule)) {
			return $this->get_steam_connector()->predefined_command($wikimodule, "wiki_to_html_plain", array($this), $pBuffer);
		}
		throw new steam_exception($this->steam_connector->get_login_user_name(), "Error: cant get module \"wiki\" from server.", 404);
	}

	public function get_version($pBuffer = 0) {
		$version = (int) $this->get_attribute("DOC_VERSION", $pBuffer);

		return $version;
	}

	public function get_previous_versions($pBuffer = 0) {
		$callback = function ($versions) {
			$result = array();
			if (is_array($versions) && !empty($versions)) {
				krsort($versions);
				$versions = array_values($versions);
				$result = $versions;
			}

			return $result;
		};

		//prevent caching
		unset($this->attributes["DOC_VERSIONS"]);

		if ($pBuffer) {
			$tid = $this->get_attribute("DOC_VERSIONS", $pBuffer);
			$this->get_steam_connector()->add_buffer_result_callback($tid, $callback);

			return $tid;
		} else {
			$versions = $this->get_attribute("DOC_VERSIONS");
			$versions = $callback($versions);

			return $versions;
		}
	}

	public function get_last_version() {
		$versions = $this->get_previous_versions();
		$keys = array_keys($versions);
		$first = $keys[0];

		return $versions[$first];
	}

	public function is_previous_version_of($pBuffer = 0) {
		return $this->get_attribute("OBJ_VERSIONOF", $pBuffer);
	}

	public function get_mimetype($pBuffer = 0) {
		$mime = trim($this->get_attribute(DOC_MIME_TYPE));
		if (!isset($mime) || empty($mime) || $mime === "unknown/unknown" || $mime === "application/x-download" || $mime === "application/download" || $mime === "application/octet-stream" || $mime === "application/x-unknown-content-type" || $mime === "application/" || $mime === "text/html" || $mime === "application/save-as" || $mime === "x-application/octet-stream" || $mime === "application/x-msdownload" || $mime === "application/foobar" || $mime === "text/x-pdf" || $mime === "image/pdf") {
			$mime = \MimetypeHelper::get_instance()->getMimeType($this->get_name());
		}

		return $mime;
	}

	public function send_custom_header($downloaderType = "A") {
		if (defined("PLATFORM_ID")) {
			$key = "X-" . PLATFORM_ID;
		} else {
			$key = "X-PHPSTEAM";
		}

		$value = "PT: " . $this->getPersistenceType();
		if ($this->getPersistenceType() === PERSISTENCE_DATABASE) {
			if (DEFAULT_CONTENT_PROVIDER === CONTENT_PROVIDER_COAL) {
				$value .= " CP: C";
			} elseif (DEFAULT_CONTENT_PROVIDER === CONTENT_PROVIDER_STEAMWEB) {
				$value .= " CP: W";
			} elseif (DEFAULT_CONTENT_PROVIDER === CONTENT_PROVIDER_DATABASE) {
				$value .= " CP: D";
			} else {
				$value .= " CP: C";
			}
		} else if (ENABLE_FILE_PERSISTENCE && $this->getPersistenceType() === PERSISTENCE_FILE_CONTENTID) {
			$value .= " FP: CID";
		}
		$value .= " D: " . $downloaderType;

		header($key . ": " . $value);
	}

	public function markAsRead() {
		$module_read_doc = $this->get_steam_connector()->get_module("table:read-documents");
		$this->steam_command($module_read_doc, "download_document", array(8, $this), 0);
	}

}
