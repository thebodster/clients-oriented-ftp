<?php
$allowed_levels = array(9,8);
require_once('sys.includes.php');

if ($_GET['do']=='edit' || isset($_POST['edit_who'])) {
	$page_title = __('Edit client','cftp_admin');
}
else {
	$page_title = __('Add new client','cftp_admin');
}

include('header.php');

$database->MySQLDB();

if ($_GET['do']=='edit') {
	//if we are editing a client, then the info to show on the form comes from the database
	$edit_id = $_GET['client'];
	$editing = $database->query("SELECT * FROM tbl_clients WHERE id=$edit_id");
	$count=mysql_num_rows($editing);
	if (!$count) {
		$process_state = 'edit_not_exists';
	}
	else {
		while($data = mysql_fetch_array($editing)) {
			$add_client_data_name = $data['name'];
			$add_client_data_user = $data['client_user'];
			$add_client_data_pass = '';
			$add_client_data_pass2 = '';
			$add_client_data_addr = $data['address'];
			$add_client_data_phone = $data['phone'];
			$add_client_data_email = $data['email'];
			$add_client_data_intcont = $data['contact'];
			if ($data['notify'] == 1) { $add_client_data_notity = 1; } else { $add_client_data_notity = 0; }
		}
	}
}
else {
	$add_client_data_name = mysql_real_escape_string($_POST['add_client_form_name']);
	$add_client_data_pass = md5(mysql_real_escape_string($_POST['add_client_form_pass']));
	$add_client_data_addr = mysql_real_escape_string($_POST['add_client_form_address']);
	$add_client_data_phone = mysql_real_escape_string($_POST['add_client_form_phone']);
	$add_client_data_email = mysql_real_escape_string($_POST['add_client_form_email']);
	$add_client_data_intcont = mysql_real_escape_string($_POST['add_client_form_intcont']);
	if(isset($_POST["add_client_form_notify"])) { $add_client_data_notity = 1; } else { $add_client_data_notity = 0; }
	// fix for showing the correct user when editing but php validation failed
	// SHOULD WE AVOID ALL THIS BY NOT SHOWING THE USERNAME FIELD WHEN EDITING?
	if (isset($_POST['edit_who'])) {
		$edit_who = $_POST['edit_who'];
		$editing_user = $database->query("SELECT * FROM tbl_clients WHERE id=$edit_who");
		while($userrow = mysql_fetch_array($editing_user)) {
			$add_client_data_user = $userrow['client_user'];
		}
	}
	else {
		$add_client_data_user = mysql_real_escape_string($_POST['add_client_form_user']);
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
	if (isset($edit_who) && $_POST['add_client_form_pass'] != '') {
		$check_password = 1;
	}

	// begin form validation
	$valid_me->validate('completed',$add_client_data_name,$validation_no_name);
	$valid_me->validate('completed',$add_client_data_email,$validation_no_email);
	$valid_me->validate('email',$add_client_data_email,$validation_invalid_mail);
	$valid_me->validate('email_exists',$add_client_data_email,$add_user_mail_exists);

	if (isset($check_password) && $check_password === 1) {
		$valid_me->validate('completed',$_POST['add_client_form_pass'],$validation_no_pass);
		$valid_me->validate('password',$_POST['add_client_form_pass'],$validation_valid_pass.' '.$validation_valid_chars);
		$valid_me->validate('length',$_POST['add_client_form_pass'],$validation_length_pass,MIN_PASS_CHARS,MAX_PASS_CHARS);
		$valid_me->validate('pass_match','',$validation_match_pass,'','',$_POST['add_client_form_pass'],$_POST['add_client_form_pass2']);
	}

	if (!isset($edit_who)) {
		// only check this values when adding a new client, not when editing
		$valid_me->validate('user_exists',$add_client_data_user,$add_user_exists);
		// user field is only checked when adding a new client because it returns an empty value when it is disabled
		$valid_me->validate('completed',$add_client_data_user,$validation_no_user);
		$valid_me->validate('length',$add_client_data_user,$validation_length_user,MIN_USER_CHARS,MAX_USER_CHARS);
		$valid_me->validate('alpha',$add_client_data_user,$validation_alpha_user);
	}
	
	if ($valid_me->return_val) { //lets continue

		if (isset($edit_who)) {
			//we are editing a client
			$editing = $database->query("SELECT * FROM tbl_clients WHERE id=$edit_who");
			$count=mysql_num_rows($editing);
			if (!$count) {
				// there is no client with the posted id
				$process_state = 'edit_not_exists';
			}
			else {
				// posted data is valid and the client does exist for editing, so do it
				$editquery = "UPDATE tbl_clients SET 
							name = '$add_client_data_name',
							address = '$add_client_data_addr',
							phone = '$add_client_data_phone',
							email = '$add_client_data_email',
							contact = '$add_client_data_intcont',";
				if (isset($check_password) && $check_password === 1) {
					$editquery .= "password = '$add_client_data_pass',";
				}
				if(isset($_POST["add_client_form_notify"])) {
					$editquery .= " notify = '1'";
				} else {
					$editquery .= " notify = '0'";
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
			// we are creating a new client
			// Create user folder if it doesn't exist
			$folder = 'upload/' . $add_client_data_user;
			if (!file_exists($folder)) {
				$success = @mkdir($folder);
	
				// if the folder was created, continue
				if ($success){
					chmod($folder, 0755);
					$folder2 = $folder.'/'.'/thumbs';
					mkdir($folder2); chmod($folder2, 0755);
		
					// Create index.php on clients folder
					$index_content = 'require_once(\'../../includes/sys.vars.php\'); $this_user = "'.$add_client_data_user.'"; $template = \'../../templates/\'.TEMPLATE_USE.\'/template.php\'; include_once($template);';
					$addwhat = '<?php ' . $index_content . ' ?>';
					$file = $folder .'/'. "index.php";   
					if (!$file_handle = fopen($file,"a")) { echo $creat_err1; }
					if (!fwrite($file_handle, $addwhat)) { echo $creat_err2; }
					fclose($file_handle);
					$linkcli = realpath($file);

					// who is creating the user?
					$this_admin = get_current_user_username();
	
					// insert user into db
					$timestampdate = time();
					$success = $database->query("INSERT INTO tbl_clients (id,name,client_user,password,address,phone,email,notify,contact,timestamp,created_by)"
					."VALUES ('NULL', '$add_client_data_name', '$add_client_data_user', '$add_client_data_pass', '$add_client_data_addr', '$add_client_data_phone', '$add_client_data_email', '$add_client_data_notity', '$add_client_data_intcont', '$timestampdate','$this_admin')");
	
					// Send notification e-mail
						// Call the e-mail sending class
					require_once('includes/classes/send-email.php');
					$notify_client = new PSend_Email();
					$notify_send = $notify_client->psend_send_email('new_client',$add_client_data_email,$add_client_data_user,$_POST['add_client_form_pass']);

					if ($notify_send == 1){
						$email_state = 'ok';
					}
					else{
						$email_state = 'err';
					}
	
					// everything went ok! :)
					$process_state = 'ok';
				}
				else {
					$process_state = 'err_mkdir';
				}
			}
			else {
				$process_state = 'err_folder_exists';
			}
		} // edit or add end
	} //after-validation code ends here

} // no form info sent ?>

	<div id="main">
		<h2><?php echo $page_title; ?></h2>

		<div class="whiteform whitebox">
	
		<?php $valid_me->list_errors(); // if the form was submited with errors, show them here ?>
		
		<?php
			if (isset($process_state)) {
				switch ($process_state) {
					case 'ok':
						$msg = __('Client added correctly','cftp_admin');
						echo system_message('ok',$msg);
					break;
					case 'err_mkdir':
						$msg = __('A folder for this client could not be created. Probably because of a server configuration.','cftp_admin');
						echo system_message('error',$msg);
					break;
					case 'err_folder_exists':
						$msg = __('The client could not be created. A folder with this name already exists.','cftp_admin');
						echo system_message('error',$msg);
					break;
					case 'edit_not_exists':
						$msg = __('There is no client with that ID to edit.','cftp_admin');
						echo system_message('error',$msg);
					break;
					case 'edit_ok':
						$msg = __('The client was edited correctly.','cftp_admin');
						echo system_message('ok',$msg);
					break;
					case 'edit_err':
						$msg = __('There was an error. Please try again.','cftp_admin');
						echo system_message('error',$msg);
					break;
				}
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

				is_complete(this.add_client_form_name,'<?php echo $validation_no_name; ?>');
				is_complete(this.add_client_form_user,'<?php echo $validation_no_user; ?>');
				is_complete(this.add_client_form_email,'<?php echo $validation_no_email; ?>');
				is_length(this.add_client_form_user,<?php echo MIN_USER_CHARS; ?>,<?php echo MAX_USER_CHARS; ?>,'<?php echo $validation_length_user; ?>');
				is_email(this.add_client_form_email,'<?php echo $validation_invalid_mail; ?>');
				is_alpha(this.add_client_form_user,'<?php echo $validation_alpha_user; ?>');
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
						is_complete(this.add_client_form_pass,'<?php echo $validation_no_pass; ?>');
						is_complete(this.add_client_form_pass2,'<?php echo $validation_no_pass2; ?>');
						is_length(this.add_client_form_pass,<?php echo MIN_PASS_CHARS; ?>,<?php echo MAX_PASS_CHARS; ?>,'<?php echo $validation_length_pass; ?>');
						is_password(this.add_client_form_pass,'<?php $chars = addslashes($validation_valid_chars); echo $validation_valid_pass." ".$chars; ?>');
						is_match(this.add_client_form_pass,this.add_client_form_pass2,'<?php echo $validation_match_pass; ?>');
					<?php
					}
				?>
	
				// show the errors or continue if everything is ok
				if (show_form_errors() == false) { return false; }
			});
		});

	</script>
	
		<form action="clientform.php" name="addclient" method="post">
			<?php if ($_GET['do']=='edit' || isset($_POST['edit_who'])) { ?>
				<input type="hidden" name="edit_who" id="edit_who" value="<?php if ($_GET['do']=='edit') { echo $_GET['client']; } elseif (isset($_POST['edit_who'])) { echo $_POST['edit_who']; } ?>" />
			<?php } ?>
			<ul class="form_fields">
				<li>
					<label for="add_client_form_name"><?php _e('Name','cftp_admin'); ?></label>
					<input name="add_client_form_name" id="add_client_form_name" class="txtfield required" value="<?php echo stripslashes($add_client_data_name); ?>" />
				</li>
				<li>
					<label for="add_client_form_user"><?php _e('Log in username','cftp_admin'); ?></label>
					<input name="add_client_form_user" id="add_client_form_user" class="txtfield required" maxlength="<?php echo MAX_USER_CHARS; ?>" value="<?php echo stripslashes($add_client_data_user); ?>" <?php if ($_GET['do']=='edit' || isset($_POST['edit_who'])) { ?>disabled="disabled"<?php }?> />
				</li>
				<li>
					<label for="add_client_form_pass"><?php _e('Password','cftp_admin'); ?></label>
					<input name="add_client_form_pass" id="add_client_form_pass" class="txtfield required" type="password" maxlength="<?php echo MAX_PASS_CHARS; ?>" />
				</li>
				<li>
					<label for="add_client_form_pass2"><?php _e('Repeat password','cftp_admin'); ?></label>
					<input name="add_client_form_pass2" id="add_client_form_pass2" class="txtfield required" type="password" maxlength="<?php echo MAX_PASS_CHARS; ?>" />
				</li>
				<li>
					<label for="add_client_form_address"><?php _e('Address','cftp_admin'); ?></label>
					<input name="add_client_form_address" id="add_client_form_address" class="txtfield" value="<?php echo stripslashes($add_client_data_addr); ?>" />
				</li>
				<li>
					<label for="add_client_form_phone"><?php _e('Telephone','cftp_admin'); ?></label>
					<input name="add_client_form_phone" id="add_client_form_phone" class="txtfield" value="<?php echo stripslashes($add_client_data_phone); ?>" />
				</li>
				<li>
					<label for="add_client_form_email"><?php _e('E-mail','cftp_admin'); ?></label>
					<input name="add_client_form_email" id="add_client_form_email" class="txtfield required" value="<?php echo stripslashes($add_client_data_email); ?>" />
				</li>
				<li>
					<label for="add_client_form_notify"><?php _e('Notify new uploads by e-mail','cftp_admin'); ?></label>
					<input type="checkbox" name="add_client_form_notify" id="add_client_form_notify" <?php if($add_client_data_notity == 1) { ?>checked="checked"<?php } ?> />
				</li>
				<li>
					<label for="add_client_form_intcont"><?php _e('Internal contact','cftp_admin'); ?></label>
					<input name="add_client_form_intcont" id="add_client_form_intcont" class="txtfield" value="<?php echo stripslashes($add_client_data_intcont); ?>" />
				</li>
				<li class="form_submit_li">
					<input type="submit" name="Submit" value="<?php if ($_GET['do']=='edit' || isset($_POST['edit_who'])) { _e('Edit account','cftp_admin'); } else { _e('Create account','cftp_admin'); } ?>" class="boton" />
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