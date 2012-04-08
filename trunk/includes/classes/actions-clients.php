<?php
/**
 * Class that handles all the actions and functions that can be applied to
 * clients accounts.
 *
 * @package		ProjectSend
 * @subpackage	Classes
 */

class ClientActions
{

	var $client = '';

	function delete_client($client) {
		global $database;
		$this->check_level = array(9,8);
		if (isset($client)) {
			$this->return_id = $database->query('SELECT client_user FROM tbl_clients WHERE id="' . $client .'"');
			$this->get_client = mysql_fetch_row($this->return_id);
			$this->client_user = $this->get_client[0];
			/** Do a permissions check */
			if (isset($this->check_level) && in_session_or_cookies($this->check_level)) {
				$this->sql = $database->query('DELETE FROM tbl_clients WHERE id="' . $client .'"');
				$this->sql = $database->query('DELETE FROM tbl_files WHERE id="' . $client .'"');
				$this->folder = "./upload/" . $this->client_user . "/";
				delete_recursive($this->folder);
			}
		}
	}

}

?>