<?php

include_once('includes/email-template.php');

// Define the messages
// -- New file uploaded
$email_strings_notify_client = array(
									'subject' => __('New file uploaded for you','cftp_admin'),
									'body' => __('A new file has been uploaded for you to download.','cftp_admin'),
									'body2' => __("If you don't want to be notified about new files, please contact the uploader.",'cftp_admin'),
									'body3' => __('You can access a list of all your files','cftp_admin'),
									'body4' => __('by logging in here','cftp_admin')
								);


$email_strings_new_client = array(
									'subject' => __('Welcome to ProjectSend','cftp_admin'),
									'body' => __('A new account was created for you. From now on, you can access the files that have been uploaded under your account using the following credentials:','cftp_admin'),
									'body2' => __('Access the system administration here','cftp_admin'),
									'body3' => __('Please contact the administrator if you need further assistance.','cftp_admin'),
									'label_user' => __('Your username','cftp_admin'),
									'label_pass' => __('Your password','cftp_admin')
								);

$email_strings_new_user = array(
									'subject' => __('Welcome to ProjectSend','cftp_admin'),
									'body' => __('A new account was created for you. From now on, you can access the system administrator using the following credentials:','cftp_admin'),
									'body2' => __('Access the system panel here','cftp_admin'),
									'body3' => __('Thank you for using ProjectSend.','cftp_admin'),
									'label_user' => __('Your username','cftp_admin'),
									'label_pass' => __('Your password','cftp_admin')
								);


class PSend_Email {

	var $email_headers = '';
	
	function email_prepare_body($filename) {
		global $email_template_header;
		global $email_template_footer;

		$this->get_body = 'emails/'.$filename;
		$this->make_body = $email_template_header;
		$this->make_body .= file_get_contents($this->get_body);
		$this->make_body .= $email_template_footer;
		return $this->make_body;
	}

	function email_set_headers() {
		$this->email_headers = 'From: '.THIS_INSTALL_SET_TITLE.' <'.ADMIN_EMAIL_ADDRESS.'>' . "\n";
		$this->email_headers .= 'Return-Path:<'.ADMIN_EMAIL_ADDRESS.'>\r\n';
		$this->email_headers .= 'MIME-Version: 1.0' . "\n";
		$this->email_headers .= 'Content-type: text/html; charset='.EMAIL_ENCODING."\r\n";
		$this->email_headers .= "Sensitivity: Private\n";
		return $this->email_headers;
	}

	// New File
	function email_new_file() {
		global $email_strings_notify_client;
		$this->email_body = $this->email_prepare_body('new-file-for-client.html');
		$this->email_body = str_replace(
									array('%SUBJECT%','%BODY1%','%BODY2%','%BODY3%','%BODY4%','%LINK%'),
									array($email_strings_notify_client['subject'],$email_strings_notify_client['body'],$email_strings_notify_client['body2'],$email_strings_notify_client['body3'],$email_strings_notify_client['body4'],BASE_URI),
									$this->email_body
								);
		return array(
					'subject' => $email_strings_notify_client['subject'],
					'body' => $this->email_body
				);
	}

	// New Client
	function email_new_client($username,$password) {
		global $email_strings_new_client;
		$this->email_body = $this->email_prepare_body('new-client.html');
		$this->email_body = str_replace(
									array('%SUBJECT%','%BODY1%','%BODY2%','%BODY3%','%LBLUSER%','%LBLPASS%','%USERNAME%','%PASSWORD%','%URI%'),
									array($email_strings_new_client['subject'],$email_strings_new_client['body'],$email_strings_new_client['body2'],$email_strings_new_client['body3'],$email_strings_new_client['label_user'],$email_strings_new_client['label_pass'],$username,$password,BASE_URI),
									$this->email_body
								);
		return array(
					'subject' => $email_strings_new_client['subject'],
					'body' => $this->email_body
				);
	}

	// New User
	function email_new_user($username,$password) {
		global $email_strings_new_user;
		$this->email_body = $this->email_prepare_body('new-user.html');
		$this->email_body = str_replace(
									array('%SUBJECT%','%BODY1%','%BODY2%','%BODY3%','%LBLUSER%','%LBLPASS%','%USERNAME%','%PASSWORD%','%URI%'),
									array($email_strings_new_user['subject'],$email_strings_new_user['body'],$email_strings_new_user['body2'],$email_strings_new_user['body3'],$email_strings_new_user['label_user'],$email_strings_new_user['label_pass'],$username,$password,BASE_URI),
									$this->email_body
								);
		return array(
					'subject' => $email_strings_new_user['subject'],
					'body' => $this->email_body
				);
	}

	function psend_send_email($type,$address,$username = '',$password = '') {
		$this->headers = $this->email_set_headers();
		switch($type) {
			case 'new_file':
				$this->mail_info = $this->email_new_file();
			break;
			case 'new_client':
				$this->mail_info = $this->email_new_client($username,$password);
			break;
			case 'new_user':
				$this->mail_info = $this->email_new_user($username,$password);
			break;
		}
		$this->subject = $this->mail_info['subject'];
		$this->body = $this->mail_info['body'];
		if(@mail($address,$this->subject,$this->body,$this->headers)) {
			return 1;
		}
		else {
			return 2;
		}
	}

}

?>