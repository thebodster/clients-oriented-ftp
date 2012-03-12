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
	private function is_user_exists($field, $err, $table, $row) {
		if (mysql_num_rows(mysql_query("SELECT * FROM $table WHERE $row = '$field'"))){
			$this->error_msg .= '<li>'.$err.'</li>';
			$this->return_val = false;
		}
	}

	function validate($val_type, $field, $err='', $min='', $max='', $pass1='', $pass2='', $table='', $row='') {
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
			case 'length':
				$this->is_length($field, $err, $min, $max);
			break;
			case 'pass_match':
				$this->is_pass_match($err, $pass1, $pass2);
			break;
			case 'user_exists':
				$this->is_user_exists($field, $err, $table, $row);
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