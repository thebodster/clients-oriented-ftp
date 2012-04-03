<?php

class FilesActions {

	var $files = array();
	
	function get_file_data_by_id($file_id) {
		global $database;
		// Query 1: File information
		$this->sql1 = $database->query('SELECT url,client_user FROM tbl_files WHERE id="' . $file_id .'"');
		$this->file_data = mysql_fetch_assoc($this->sql1);
		$this->file_information = array(
									'url' => $this->file_data['url'],
									'client_user' => $this->file_data['client_user']
									);


		// Query 2: Client ID
		$this->sql2 = $database->query('SELECT id FROM tbl_clients WHERE client_user="' . $this->file_information['client_user'] .'"');
		$this->client_data = mysql_fetch_row($this->sql2);
		$this->file_information['client_id'] = $this->client_data[0];

		return $this->file_information;
	}

	function delete_files($file_id) {
		global $database;
		$this->check_level = array(9,8);
		if (isset($file_id)) {
			$this->file_information = $this->get_file_data_by_id($file_id);
			$this->file_url = $this->file_information['url'];
			$this->client_user = $this->file_information['client_user'];
			$this->client_id = $this->file_information['client_id'];
			// do a permissions check
			if (isset($this->check_level) && in_session_or_cookies($this->check_level)) {
				// delete from database
				$this->sql = $database->query('DELETE FROM tbl_files WHERE client_user="' . $this->client_user .'" AND id="' . $file_id . '"');
				// make the filename var
				$this->gone = 'upload/' . $this->client_user .'/' . $this->file_url;
				$this->thumb = 'upload/' . $this->client_user .'/thumbs/' . $this->file_url;
				delfile($this->gone);
				if (file_exists($this->thumb)) {
					delfile($this->thumb);
				}
			}
		}
	}
	
	function change_files_hide_status($file_id,$change_to) {
		global $database;
		$this->check_level = array(9,8,7);
		if (isset($file_id)) {
			$this->file_information = $this->get_file_data_by_id($file_id);
			$this->client_id = $this->file_information['client_id'];
			// do a permissions check
			if (isset($this->check_level) && in_session_or_cookies($this->check_level)) {
				$this->sql = $database->query('UPDATE tbl_files SET hidden='.$change_to.' WHERE id="' . $file_id . '"');
			}
		}
	}

}
?>