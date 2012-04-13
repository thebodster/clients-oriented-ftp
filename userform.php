<?php
$allowed_levels = array(9);
require_once('sys.includes.php');

if ($_GET['do']=='edit' || isset($_POST['edit_who'])) {
	$page_title = __('Edit system user','cftp_admin');
}
else {
	$page_title = __('Add system user','cftp_admin');
}

include('header.php');

$database->MySQLDB();

if ($_GET['do']=='edit') {
	//if we are editing a client, then the info to show on the form comes from the database
	$edit_id = $_GET['user'];
	$editing = $database->query("SELECT * FROM tbl_users WHERE id=$edit_id");
	$count=mysql_num_rows($editing);
	if (!$count) {
		$process_state = 'edit_not_exists';
	}
	else {
		while($data = mysql_fetch_array($editing)) {
			$add_user_data_name = $data['name'];
			$add_user_data_user = $data['user'];
			$add_user_data_pass = '';
			$add_user_data_pass2 = '';
			$add_user_data_email = $data['email'];
			$add_user_data_level = $data['level'];
		}
	}
}
else {
	$add_user_data_name = mysql_real_escape_string($_POST['add_user_form_name']);
	$add_user_data_pass = md5(mysql_real_escape_string($_POST['add_user_form_pass']));
	$add_user_data_pass2 = mysql_real_escape_string(md5($_POST['add_user_form_pass2']));
	$add_user_data_email = mysql_real_escape_string($_POST['add_user_form_email']);
	$add_user_data_level = mysql_real_escape_string($_POST['add_user_form_level']);
	// fix for showing the correct user when editing but php validation failed
	// SHOULD WE AVOID ALL THIS BY NOT SHOWING THE USERNAME FIELD WHEN EDITING?
	if (isset($_POST['edit_who'])) {
		$edit_who = $_POST['edit_who'];
		$editing_user = $database->query("SELECT * FROM tbl_users WHERE id=$edit_who");
		while($userrow = mysql_fetch_array($editing_user)) {
			$add_user_data_user = $userrow['user'];
		}
	}
	else {
		$add_user_data_user = mysql_real_escape_string($_POST['add_user_form_user']);
	}
}

require_once('includes/classes/form-validation.php');

if ($_POST) {
	
	// set this when editing
	$edit_who = $_POST['edit_who'];

	// cases for password checking
	if (!isset($edit_who)) {
		$check_password = 1;
	}
	if (isset($edit_who) && $_POST['add_user_form_pass'] != '') {
		$check_password = 1;
	}

	// begin form validation
	$valid_me->validate('completed',$add_user_data_name,$validation_no_name);
	$valid_me->validate('completed',$add_user_data_email,$validation_no_email);
	$valid_me->validate('completed',$add_user_data_level,$validation_no_level); // just a precaution
	$valid_me->validate('email',$add_user_data_email,$validation_invalid_mail);
	$valid_me->validate('email_exists',$add_user_data_email,$add_user_mail_exists);

	if (isset($check_password) && $check_password === 1) {
		$valid_me->validate('completed',$_POST['add_user_form_pass'],$validation_no_pass);
		$valid_me->validate('password',$_POST['add_user_form_pass'],$validation_valid_pass.' '.$validation_valid_chars);
		$valid_me->validate('length',$_POST['add_user_form_pass'],$validation_length_pass,MIN_PASS_CHARS,MAX_PASS_CHARS);
		$valid_me->validate('pass_match','',$validation_match_pass,'','',$_POST['add_user_form_pass'],$_POST['add_user_form_pass2']);
	}

	if (!isset($edit_who)) {
		// only check this values when adding a new uset, not when editing
		$valid_me->validate('user_exists',$add_user_data_user,$add_user_exists);
		// user field is only checked when adding a new client because it returns an empty value when it is disabled
		$valid_me->validate('completed',$add_user_data_user,$validation_no_user);
		$valid_me->validate('alpha',$add_user_data_user,$validation_alpha_user);
		$valid_me->validate('length',$add_user_data_user,$validation_length_user,MIN_USER_CHARS,MAX_USER_CHARS);
	}

	if ($valid_me->return_val) { //lets continue

		if (isset($edit_who)) {
			//we are editing a user
			$editing = $database->query("SELECT * FROM tbl_users WHERE id=$edit_who");
			$count=mysql_num_rows($editing);
			if (!$count) {
				// there is no user with the posted id
				$process_state = 'edit_not_exists';
			}
			else {
				// posted data is valid and the user does exist for editing, so do it
				$editquery = "UPDATE tbl_users SET 
										name = '$add_user_data_name',
										email = '$add_user_data_email',
										level = '$add_user_data_level'";
				if (isset($check_password) && $check_password === 1) {
					$editquery .= ", password = '$add_user_data_pass'";
				}
				$editquery .= " WHERE id = $edit_who";
				$success = $database->query($editquery);
				if ($success){
					$process_state = 'edit_ok';
				}
				else {
					$process_state = 'edit_err';
				}
			}
		}
		else {
			//we are adding a new user to the system

			// add new user to DB
			$timestampdate = time();
			$success = mysql_query("INSERT INTO tbl_users (id,user,password,name,email,level,timestamp)"
			."VALUES ('NULL', '$add_user_data_user', '$add_user_data_pass', '$add_user_data_name', '$add_user_data_email','$add_user_data_level', '$timestampdate')");
			
			if ($success){
				$process_state = 'ok';
	
				// Send notification e-mail
					// Call the e-mail sending class
				require_once('includes/classes/send-email.php');
				$notify_user = new PSend_Email();
				$notify_send = $notify_user->psend_send_email('new_user',$add_user_data_email,$add_user_data_user,$_POST['add_user_form_pass']);
	
				// send account data by email
				if ($notify_send == 1){
					$email_state = 'ok';
				}
				else {
					$email_state = 'err';
				}
			}
			else {
				$process_state = 'err';
			}
		} // edit or add end
	} //validation ends here
	

} // no form info sent ?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>
	
		<div class="whiteform whitebox">
		
		<?php $valid_me->list_errors(); // if the form was submited with errors, show them here ?>
		
		<?php
			if (isset($process_state)) {
				// get the process state and show the corresponding ok or error message
				switch ($process_state) {
					case 'ok':
						$msg = __('User added correctly.','cftp_admin');
						echo system_message('ok',$msg);
					break;
					case 'err':
						$msg = __('There was an error. Please try again.','cftp_admin');
						echo system_message('error',$msg);
					break;
					case 'edit_not_exists':
						$msg = __('There is no user with that ID to edit.','cftp_admin');
						echo system_message('error',$msg);
					break;
					case 'edit_ok':
						$msg = __('The user was edited correctly.','cftp_admin');
						echo system_message('ok',$msg);
					break;
					case 'edit_err':
						$msg = __('There was an error. Please try again.','cftp_admin');
						echo system_message('error',$msg);
					break;
				}
				// ok or error message for the email notification
				switch ($email_state) {
					case 'ok':
						$msg = __('An e-mail notification with login information was sent to your client.','cftp_admin');
						echo system_message('ok',$msg);
					break;
					case 'err':
						$msg = __("E-mail notification couldn't be sent.",'cftp_admin');
						echo system_message('error',$msg);
					break;
				}
			}
			else {
			// if not $process_state is set, it means we are just entering for the first time
		?>

	<script type="text/javascript">
		$(document).ready(function() {
			$("form").submit(function() {
				clean_form(this);

				is_complete(this.add_user_form_name,'<?php echo $validation_no_name; ?>');
				is_complete(this.add_user_form_user,'<?php echo $validation_no_user; ?>');
				is_complete(this.add_user_form_email,'<?php echo $validation_no_email; ?>');
				is_complete(this.add_user_form_level,'<?php echo $validation_no_level; ?>');
				is_length(this.add_user_form_user,<?php echo MIN_USER_CHARS; ?>,<?php echo MAX_USER_CHARS; ?>,'<?php echo $validation_length_user; ?>');
				is_email(this.add_user_form_email,'<?php echo $validation_invalid_mail; ?>');
				is_alpha(this.add_user_form_user,'<?php echo $validation_alpha_user; ?>');
				<?php
					// This should be re-done!!
					if($_POST) {
						if (isset($check_password) && $check_password === 1) {
							$js_check_password = 1;
						}
					}
					else {
						if ($_GET['do']=='edit') {
						}
						else {
							$js_check_password = 1;
						}
					}
					if (isset($js_check_password) && $js_check_password === 1) {
					?>
						is_complete(this.add_user_form_pass,'<?php echo $validation_no_pass; ?>');
						is_complete(this.add_user_form_pass2,'<?php echo $validation_no_pass2; ?>');
						is_length(this.add_user_form_pass,<?php echo MIN_PASS_CHARS; ?>,<?php echo MAX_PASS_CHARS; ?>,'<?php echo $validation_length_pass; ?>');
						is_password(this.add_user_form_pass,'<?php $chars = addslashes($validation_valid_chars); echo $validation_valid_pass." ".$chars; ?>');
						is_match(this.add_user_form_pass,this.add_user_form_pass2,'<?php echo $validation_match_pass; ?>');
					<?php
					}
				?>
	
				// show the errors or continue if everything is ok
				if (show_form_errors() == false) { return false; }
			});
		});
	
	</script>

		<form action="userform.php" name="adduser" method="post">
			<?php if ($_GET['do']=='edit' || isset($_POST['edit_who'])) { ?>
				<input type="hidden" name="edit_who" id="edit_who" value="<?php if ($_GET['do']=='edit') { echo $_GET['user']; } elseif (isset($_POST['edit_who'])) { echo $_POST['edit_who']; } ?>" />
			<?php } ?>
			<ul class="form_fields">
				<li>
					<label for="add_user_form_name"><?php _e('Name','cftp_admin'); ?></label>
					<input name="add_user_form_name" id="add_user_form_name" class="txtfield required" value="<?php echo stripslashes($add_user_data_name); ?>" />
				</li>
				<li>
					<label for="add_user_form_user"><?php _e('Log in username','cftp_admin'); ?></label>
					<input name="add_user_form_user" id="add_user_form_user" class="txtfield required" maxlength="<?php echo MAX_USER_CHARS; ?>" value="<?php echo stripslashes($add_user_data_user); ?>" <?php if ($_GET['do']=='edit' || isset($_POST['edit_who'])) { ?>disabled="disabled"<?php }?> />				</li>
				<li>
					<label for="add_user_form_pass"><?php _e('Log in password','cftp_admin'); ?></label>
					<input name="add_user_form_pass" id="add_user_form_pass" class="txtfield required" type="password" maxlength="<?php echo MAX_PASS_CHARS; ?>" />
				</li>
				<li>
					<label for="add_user_form_pass2"><?php _e('Repeat password','cftp_admin'); ?></label>
					<input name="add_user_form_pass2" id="add_user_form_pass2" class="txtfield required" type="password" maxlength="<?php echo MAX_PASS_CHARS; ?>" />
				</li>
				<li>
					<label for="add_user_form_email"><?php _e('E-mail','cftp_admin'); ?></label>
					<input name="add_user_form_email" id="add_user_form_email" class="txtfield required" value="<?php echo stripslashes($add_user_data_email); ?>" />
				</li>
				<li>
					<label for="add_user_form_level"><?php _e('Role','cftp_admin'); ?></label>
					<select name="add_user_form_level" id="add_user_form_level" class="txtfield">
						<option value="9" <?php if( $add_user_data_level == '9') { echo 'selected="selected"'; } ?>><?php echo USER_ROLE_LVL_9; ?></option>
						<option value="8" <?php if( $add_user_data_level == '8') { echo 'selected="selected"'; } ?>><?php echo USER_ROLE_LVL_8; ?></option>
						<option value="7" <?php if( $add_user_data_level == '7') { echo 'selected="selected"'; } ?>><?php echo USER_ROLE_LVL_7; ?></option>
					</select>
				</li>
				<li class="form_submit_li">
					<input type="submit" name="Submit" value="<?php if ($_GET['do'] == 'edit' || isset($_POST['edit_who'])) { _e('Modify user','cftp_admin'); } else { _e('Add user','cftp_admin'); } ?>" class="boton" />
				</li>
			</ul>

			<?php
				if ($_GET['do'] != 'edit' && empty($_POST['edit_who'])) {
					$msg = __('This account information will be e-mailed to the address supplied above','cftp_admin');
					echo system_message('info',$msg);
				}
			?>
	
		</form>

		<?php } ?>
		
	</div>

</div>

<?php
	$database->Close();
	include('footer.php');
?>