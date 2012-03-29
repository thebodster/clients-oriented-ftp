<?php
$validation_errors_title = __('The following errors were found','cftp_admin');
$before_error = '<div class="message message_error"><p><strong>'.$validation_errors_title.'</strong>:</p><ol>';
$after_error = '</ol></div>';

class validate_form {

	var $error_msg;
	var $error_complete;
	var $return_val = true;

	// check for empty field
	private function is_complete($field, $err) {
		if (strlen(trim($field)) == 0) {
			$this->error_msg .= '<li>'.$err.'</li>';
			$this->return_val = false;
		}
	}

	// check for valid email address
	private function is_email($field, $err) {
		if(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $field)) {
			$this->error_msg .= '<li>'.$err.'</li>';
			$this->return_val = false;
		}
	}

	// check if data is alphanumeric 
	private function is_alpha($field, $err) {
		if(preg_match('/[^0-9A-Za-z]/', $field)) {
			$this->error_msg .= '<li>'.$err.'</li>';
			$this->return_val = false;
		}
	}

	// check if password contains valid characters 
	private function is_password($field, $err) {
		$allowed_numbers = array('0','1','2','3','4','5','6','7','8','9');
		$allowed_lower = range('a','z');
		$allowed_upper = range('A','Z');
		$allowed_symbols = array('`','!','"','?','$','%','^','&','*','(',')','_','-','+','=','{','[','}',']',':',';','@','~','#','|','<',',','>','.',"'","/",'\\');
		$allowed_characters = array_merge($allowed_numbers,$allowed_lower,$allowed_upper,$allowed_symbols);

		$passw = str_split($field);
		$char_errors = 0;
		foreach ($passw as $p) {
			if(!in_array($p,$allowed_characters, TRUE)) {
				$char_errors++;
			}
		}
		if($char_errors > 0) {
			$this->error_msg .= '<li>'.$err.'</li>';
			$this->return_val = false;
		}
	}

	// check if the character count is within range
	private function is_length($field, $err, $min, $max) {
		if(strlen($field) < $min || strlen($field) > $max){
			$this->error_msg .= '<li>'.$err.'</li>';
			$this->return_val = false;
		}
	}

	// check if both password filds match
	function is_pass_match($err, $pass1, $pass2) {
		if($pass1 != $pass2) {
			$this->error_msg .= '<li>'.$err.'</li>';
			$this->return_val = false;
		}
	}

	// check for empty field
	private function is_user_exists($field, $err) {
		if (mysql_num_rows(mysql_query("SELECT * FROM tbl_clients WHERE client_user = '$field'")) || mysql_num_rows(mysql_query("SELECT * FROM tbl_users WHERE user = '$field'"))){
			$this->error_msg .= '<li>'.$err.'</li>';
			$this->return_val = false;
		}
	}

	private function is_email_exists($field, $err) {
		if (mysql_num_rows(mysql_query("SELECT * FROM tbl_clients WHERE email = '$field'")) || mysql_num_rows(mysql_query("SELECT * FROM tbl_users WHERE email = '$field'"))){
			$this->error_msg .= '<li>'.$err.'</li>';
			$this->return_val = false;
		}
	}

	function validate($val_type, $field, $err='', $min='', $max='', $pass1='', $pass2='', $row='') {
		switch($val_type) {
			case 'completed':
				$this->is_complete($field, $err);
			break;
			case 'email':
				$this->is_email($field, $err);
			break;
			case 'alpha':
				$this->is_alpha($field, $err);
			break;
			case 'password':
				$this->is_password($field, $err);
			break;
			case 'length':
				$this->is_length($field, $err, $min, $max);
			break;
			case 'pass_match':
				$this->is_pass_match($err, $pass1, $pass2);
			break;
			case 'user_exists':
				$this->is_user_exists($field, $err);
			break;
			case 'email_exists':
				$this->is_email_exists($field, $err);
			break;
		}
	}

	function list_errors() {
		if (!empty($this->error_msg)) {
			$this->error_msg = $GLOBALS['before_error'].$this->error_msg.$GLOBALS['after_error']; // concatenate container div and errors
			echo $this->error_msg;
			$this->return_val = false;
			$this->error_msg = ''; // reset errors list
		}
		else {
			$this->return_val = true;
		}
	}
	
}

$valid_me = new validate_form();
?>