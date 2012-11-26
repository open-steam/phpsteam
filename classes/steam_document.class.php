<?php
/**
 * Implements the steam_document class
 *
 * Longer description follows
 *
 * PHP versions 5
 *
 * @package PHPsTeam
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Alexander Roth <aroth@it-roth.de>, Dominik Niehus <nicke@upb.de>
 */

/**
 * steam_document
 *
 * Longer description follows
 *
 * @package PHPsTeam
 */

class steam_document extends steam_object
{
	private $_persistence;

	public function getPersistence() {
		if (!isset($_persistence)) {
			$docPersistenceType = $this->get_attribute(DOC_PERSISTENCE_TYPE);
			if ($docPersistenceType === PERSISTENCE_FILE_UID) {
				$this->_persistence = \OpenSteam\Persistence\FileUidPersistence::getInstance();
			} else if ($docPersistenceType === PERSISTENCE_DATABASE) {
				$this->_persistence = \OpenSteam\Persistence\DatabasePersistence::getInstance();
			} else {
				$this->_persistence = \OpenSteam\Persistence\DatabasePersistence::getInstance();
			}
		}
		return $this->_persistence;
	}

	public function migratePersistence($toPersistenceType) {
		$currentPersistenceType = $this->get_attribute(DOC_PERSISTENCE_TYPE);
		if ($currentPersistenceType === $toPersistenceType) {
			return;
		}
		if (($currentPersistenceType === PERSISTENCE_DATABASE) && ($toPersistenceType === PERSISTENCE_FILE_UID)) {
			//get content from database
			$content = $this->get_content();

			//new persistence
			$newPersistence = \OpenSteam\Persistence\FileUidPersistence::getInstance();
			//change persistence without creating a new document version
			$newPersistence->save($this, $content, 0, true);

			//change persistence type
			$this->set_attribute(DOC_PERSISTENCE_TYPE, PERSISTENCE_FILE_UID);
			$this->_persistence = $newPersistence;
		} else if (($currentPersistenceType === PERSISTENCE_FILE_UID) && ($toPersistenceType === PERSISTENCE_DATABASE)) {
			//get content from database
			$content = $this->get_content();

			//cleanup file persistence
			$this->_persistence->lowDeleteContentFile($this);

			//new persistence
			$newPersistence = \OpenSteam\Persistence\DatabasePersistence::getInstance();
			//change persistence without creating a new document version
			$newPersistence->save($this, $content, 0, true);

			//change persistence type
			$this->set_attribute(DOC_PERSISTENCE_TYPE, PERSISTENCE_DATABASE);
			$this->_persistence = $newPersistence;

		}
	}

	public function get_type() {
		return CLASS_DOCUMENT | CLASS_OBJECT;
	}
	
	/**
	 *function download:
	 *
	 * @return success or not
	 */
	public function download($type = DOWNLOAD_ATTACHMENT) {
		if ($type === DOWNLOAD_ATTACHMENT) {
			$downloaderClass = "\\OpenSteam\\Persistence\\Downloader\\AttachmentDownloader";
		} else if ($type === DOWNLOAD_IMAGE)  {
			$downloaderClass = "\\OpenSteam\\Persistence\\Downloader\\ImageDownloader";
		} else if ($type === DOWNLOAD_INLINE) {
			$downloaderClass = "\\OpenSteam\\Persistence\\Downloader\\InlineDownloader";
		} else if ($type === DOWNLOAD_RANGE) {
			$downloaderClass = "\\OpenSteam\\Persistence\\Downloader\\RangeDownloader";
		}
		return $downloaderClass::download($this);
	}

	/**
	 * function get_readers:
	 *
	 * @param $pBuffer
	 *
	 * @return
	 */
	public function get_readers( $pBuffer = 0 )
	{
		$module_read_doc = $this->get_steam_connector()->get_module("table:read-documents");
		return $this->steam_command(
		$module_read_doc,
			"get_readers",
		array( $this ),
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
	public function is_reader( $pUser = "", $pBuffer = 0  )
	{
		$pUser = ( empty( $pUser ) ) ? $this->get_steam_connector()->get_current_steam_user() : $pUser;
		$module_read_doc = $this->get_steam_connector()->get_module("table:read-documents");
		return $this->steam_command(
		$module_read_doc,
			"is_reader",
		array( $this, $pUser ),
		$pBuffer
		);
	}

	public function set_initial_content(&$pContent) {
		$result = $this->getPersistence()->initialSave($this, $pContent);
		return $result;
	}

	/**
	 * function set_content:
	 *
	 * Sets the content of this document
	 * @param string $pContent document's content
	 * @param Boolean $pBuffer send now or buffer request?
	 * @return int content size
	 */
	public function set_content(&$pContent, $pBuffer = 0) {
		$result = $this->getPersistence()->save($this, $pContent, $pBuffer);
		unset($this->attributes[OBJ_VERSIONOF]);
		unset($this->attributes[DOC_VERSION]);
		unset($this->attributes[DOC_VERSIONS]);
		unset($this->attributes[DOC_LAST_MODIFIED]);
		unset($this->attributes[DOC_USER_MODIFIED]);
		unset($this->attributes[DOC_SIZE]);
		return $result;
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
	public function get_content_size($pBuffer = 0)
	{
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
	public function get_content_id( $pBuffer = 0 )
	{
		$result = $this->steam_command(
		$this,
			"get_content_id",
		array(),
		$pBuffer
		);
		if ( $pBuffer == 0 )
		{
			$this->attributes[ "DOC_ID" ] = $result;
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
	public function get_content($pBuffer = 0)
	{
		return $this->getPersistence()->load($this, $pBuffer);
	}

	public function delete($pBuffer = 0) {
		$this->getPersistence()->delete($this, $pBuffer);
		return parent::delete($pBuffer);
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
			return $this->get_steam_connector()->predefined_command( $wikimodule, "wiki_to_html_plain", array( $this ), $pBuffer);
		}
		throw new steam_exception( $this->steam_connector->get_login_user_name(), "Error: cant get module \"wiki\" from server.", 404 );
	}

	public function get_version($pBuffer = 0)
	{
		$version = (int) $this->get_attribute("DOC_VERSION", $pBuffer);
		return $version;
	}

	public function get_previous_versions($pBuffer = 0){
		$callback = function($versions) {
			$result = array();
			if(is_array($versions) && !empty($versions)){
				krsort($versions);
				$versions = array_values($versions);
				$result = $versions;
			}
			return $result;
		};

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
  
	public function is_previous_version_of($pBuffer = 0) {
		return $this->get_attribute("OBJ_VERSIONOF", $pBuffer);
	}
}
?>