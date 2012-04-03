<?php

class UserActions {

	var $user = '';

	function delete_user($user) {
		global $database;
		$this->check_level = array(9);
		if (isset($user)) {
			// do a permissions check
			if (isset($this->check_level) && in_session_or_cookies($this->check_level)) {
				$this->sql = $database->query('DELETE FROM tbl_users WHERE id="' . $user .'"');
			}
		}
	}

}
?>