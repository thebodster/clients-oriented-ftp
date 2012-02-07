<?php
// lang vars user acorss the system
$validation_no_name = __('Name was not completed','cftp_admin');
$validation_no_user = __('Username was not completed','cftp_admin');
$validation_no_pass = __('Password was not completed','cftp_admin');
$validation_no_pass2 = __('Password verification was not completed','cftp_admin');
$validation_no_email = __('E-mail was not completed','cftp_admin');
$validation_invalid_mail = __('E-mail address is not valid','cftp_admin');
$validation_alpha_user = __('Username must be alphanumeric (a-z,A-Z,0-9 allowed)','cftp_admin');
$validation_alpha_pass = __('Password must be alphanumeric (a-z,A-Z,0-9 allowed)','cftp_admin');
$validation_match_pass = __('Passwords did not match','cftp_admin');
$validation_no_level = __('User level was not specified','cftp_admin');
$add_user_exists = __('A user with this login name already exists.','cftp_admin');
$add_user_mail_exists = __('A user with this e-mail address already exists.','cftp_admin');

// length vars
$validation_length_usr_1 = __('Username','cftp_admin');
$validation_length_pass_1 = __('Password','cftp_admin');
$validation_length_1 = __('length should be between','cftp_admin');
$validation_length_2 = __('and','cftp_admin');
$validation_length_3 = __('characters long','cftp_admin');
$validation_length_user = $validation_length_usr_1.' '.$validation_length_1.' '.MIN_USER_CHARS.' '.$validation_length_2.' '.MAX_USER_CHARS.' '.$validation_length_3;
$validation_length_pass = $validation_length_pass_1.' '.$validation_length_1.' '.MIN_PASS_CHARS.' '.$validation_length_2.' '.MAX_PASS_CHARS.' '.$validation_length_3;

// file upload
$validation_no_filename = __('File Name was not specified','cftp_admin');
$validation_no_description = __('File description was not specified','cftp_admin');
$validation_no_file = __('No file was selected','cftp_admin');
$validation_no_client = __('Client was not specified','cftp_admin');
$file_upload_ok = __('File sent correctly','cftp_admin');
?>