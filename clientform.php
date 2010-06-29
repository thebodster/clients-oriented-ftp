<?php
$allowed_levels = array(9,8);
require_once('includes/includes.php');

if ($_GET['do']=='edit' || isset($_POST['edit_who'])) {
	$page_title = $page_title_editclient;
}
else {
	$page_title = $page_title_newclient;
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

require_once('includes/form_validation_class.php');

if ($_POST) {

	// set this when editing
	$edit_who = $_POST['edit_who'];

	// begin form validation
	$valid_me->validate('completed',$add_client_data_name,$validation_no_name);
	$valid_me->validate('completed',$_POST['add_client_form_pass'],$validation_no_pass);
	$valid_me->validate('completed',$add_client_data_email,$validation_no_email);
	$valid_me->validate('email',$add_client_data_email,$validation_invalid_mail);
	$valid_me->validate('alpha',$_POST['add_client_form_pass'],$validation_alpha_pass);
	$valid_me->validate('length',$_POST['add_client_form_pass'],$validation_length_pass,MIN_PASS_CHARS,MAX_PASS_CHARS);
	$valid_me->validate('pass_match','',$validation_match_pass,'','',$_POST['add_client_form_pass'],$_POST['add_client_form_pass2']);

	if (!isset($edit_who)) {
		// only check this values when adding a new client, not when editing
		$valid_me->validate('user_exists',$add_client_data_user,$add_client_exists,'','','','','tbl_clients','client_user');
		$valid_me->validate('user_exists',$add_client_data_email,$add_client_mail_exists,'','','','','tbl_clients','email');
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
							password = '$add_client_data_pass',
							name = '$add_client_data_name',
							address = '$add_client_data_addr',
							phone = '$add_client_data_phone',
							email = '$add_client_data_email',
							contact = '$add_client_data_intcont'";
				if(isset($_POST["add_client_form_notify"])) {
					$editquery .= ", notify = '1'";
				} else {
					$editquery .= ", notify = '0'";
				}
				$editquery .= " WHERE id = $edit_who";

				$success = mysql_query($editquery);
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
			$folder = 'upload/' . $add_client_data_user . '/';
			if (!file_exists($folder)) {
				$success = @mkdir($folder);
	
				// if the folder was created, continue
				if ($success){
					chmod($folder, 0755);
					$folder2 = 'upload/' . $add_client_data_user . '/thumbs/';
					mkdir($folder2); chmod($folder2, 0755);
		
					// Create index.php on clients folder
					$index_content = '$this_user = "' . $add_client_data_user . '" ; include_once(\'../../templates/default/template.php\');';
					$addwhat = '<?php ' . $index_content . ' ?>';
					$file = $folder . "index.php";   
					if (!$file_handle = fopen($file,"a")) { echo $creat_err1; }
					if (!fwrite($file_handle, $addwhat)) { echo $creat_err2; }
					fclose($file_handle);
					$linkcli = realpath($file);
	
					// insert user into db
					$timestampdate = time();
					$success = mysql_query("INSERT INTO tbl_clients (id,name,client_user,password,address,phone,email,notify,contact,timestamp)"
					."VALUES ('NULL', '$add_client_data_name', '$add_client_data_user', '$add_client_data_pass', '$add_client_data_addr', '$add_client_data_phone', '$add_client_data_email', '$add_client_data_notity', '$add_client_data_intcont', '$timestampdate')");
	
					// prepare email using the template
					$email_body = file_get_contents('emails/newclient.php');
	
					$email_body = str_replace('%BODY1%',$add_client_mail_body,$email_body);
					$email_body = str_replace('%BODY2%',$add_client_mail_body_2,$email_body);
					$email_body = str_replace('%BODY3%',$add_client_mail_body_3,$email_body);
					$email_body = str_replace('%LBLUSER%',$add_mail_body_user,$email_body);
					$email_body = str_replace('%LBLPASS%',$add_mail_body_pass,$email_body);
					$email_body = str_replace('%URI%',$baseuri,$email_body);
					$email_body = str_replace('%SUBJECT%',$add_client_mail_subject,$email_body);
					$email_body = str_replace('%USERNAME%',$add_client_data_user,$email_body);
					$email_body = str_replace('%PASSWORD%',$_POST['add_client_form_pass'],$email_body);
	
					// send the email
					$confirmmail = @mail($add_client_data_email, $add_client_mail_subject, $email_body, "From:<$admin_email_address>\r\nReply-to:<$admin_email_address>\r\nContent-type: text/html; charset=us-ascii");
					if ($confirmmail){
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
						echo '<div class="message message_ok"><p>'.$add_client_ok.'</p></div>';
					break;
					case 'err_mkdir':
						echo '<div class="message message_error"><p>'.$add_client_folder_error.' <strong>'.$add_client_data_user.'</strong></p></div>';
					break;
					case 'err_folder_exists':
						echo '<div class="message message_error"><p>'.$add_client_error.'</p></div>';
					break;
					case 'edit_not_exists':
						echo '<div class="message message_error"><p>'.$edit_client_exists.'</p></div>';
					break;
					case 'edit_ok':
						echo '<div class="message message_ok"><p>'.$edit_client_ok.'</p></div>';
					break;
					case 'edit_err':
						echo '<div class="message message_error"><p>'.$edit_client_error.'</p></div>';
					break;
				}
				switch ($email_state) {
					case 'ok':
						echo '<div class="message message_ok"><p>'.$add_client_notify_ok.'</p></div>';
					break;
					case 'err':
						echo '<div class="message message_error"><p>'.$add_client_notify_error.'</p></div>';
					break;
				}
			}
			else {
			// if not $process_state is set, it means we are just entering for the first time
		?>
	
	<?php include_once('includes/js/js.validations.php'); ?>

	<script type="text/javascript">
	
		window.onload = default_field;

		var js_err_name = "<?php echo $validation_no_name; ?>"
		var js_err_user = "<?php echo $validation_no_user; ?>"
		var js_err_pass = "<?php echo $validation_no_pass; ?>"
		var js_err_pass2 = "<?php echo $validation_no_pass2; ?>"
		var js_err_email = "<?php echo $validation_no_email; ?>"
		var js_err_invalid_mail = "<?php echo $validation_invalid_mail; ?>"
		var js_err_pass_mismatch = "<?php echo $validation_match_pass; ?>"
		var js_err_user_length = "<?php echo $validation_length_user; ?>"
		var js_err_pass_length = "<?php echo $validation_length_pass; ?>"
		var js_err_user_chars = "<?php echo $validation_alpha_user; ?>"
		var js_err_pass_chars = "<?php echo $validation_alpha_pass; ?>"

		function validateform(theform){
			is_complete(theform.add_client_form_name,js_err_name);
			is_complete(theform.add_client_form_user,js_err_user);
			is_complete(theform.add_client_form_pass,js_err_pass);
			is_complete(theform.add_client_form_pass2,js_err_pass2);
			is_complete(theform.add_client_form_email,js_err_email);
			is_length(theform.add_client_form_user,<?php echo MIN_USER_CHARS; ?>,<?php echo MAX_USER_CHARS; ?>,js_err_user_length);
			is_length(theform.add_client_form_pass,<?php echo MIN_PASS_CHARS; ?>,<?php echo MAX_PASS_CHARS; ?>,js_err_pass_length);
			is_email(theform.add_client_form_email,js_err_invalid_mail);
			is_alpha(theform.add_client_form_user,js_err_user_chars);
			is_alpha(theform.add_client_form_pass,js_err_pass_chars);
			is_match(theform.add_client_form_pass,theform.add_client_form_pass2,js_err_pass_mismatch);
			// show the errors or continue if everything is ok
			if (error_list != '') {
				alert(error_title+error_list)
				error_list = '';
				return false;
			}
		}

	</script>
	
		<form action="clientform.php" name="addclient" method="post" onsubmit="return validateform(this);">
			<?php if ($_GET['do']=='edit') { ?>
				<input type="hidden" name="edit_who" id="edit_who" value="<?php echo $_GET['client']; ?>" />
			<?php } ?>
			<table border="0" cellspacing="1" cellpadding="1">
			  <tr>
				<td width="40%"><?php echo $add_client_label_name; ?></td>
				<td><input name="add_client_form_name" id="add_client_form_name" class="txtfield" value="<?php echo $add_client_data_name; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_client_label_user; ?></td>
				<td><input name="add_client_form_user" id="add_client_form_user" class="txtfield" maxlength="<?php echo MAX_USER_CHARS; ?>" value="<?php echo $add_client_data_user; ?>" <?php if ($_GET['do']=='edit' || isset($_POST['edit_who'])) { ?>disabled="disabled"<?php }?> /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_client_label_pass; ?></td>
				<td><input name="add_client_form_pass" id="add_client_form_pass" class="txtfield" type="password" maxlength="<?php echo MAX_PASS_CHARS; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_client_label_pass2; ?></td>
				<td><input name="add_client_form_pass2" id="add_client_form_pass2" class="txtfield" type="password" maxlength="<?php echo MAX_PASS_CHARS; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_client_label_addr; ?></td>
				<td><input name="add_client_form_address" id="add_client_form_address" class="txtfield" value="<?php echo $add_client_data_addr; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_client_label_phone; ?></td>
				<td><input name="add_client_form_phone" id="add_client_form_phone" class="txtfield" value="<?php echo $add_client_data_phone; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_client_label_email; ?></td>
				<td><input name="add_client_form_email" id="add_client_form_email" class="txtfield" value="<?php echo $add_client_data_email; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_client_label_notify; ?></td>
				<td><input type="checkbox" name="add_client_form_notify" id="add_client_form_notify" <?php if($add_client_data_notity == 1) { ?>checked="checked"<?php } ?> /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_client_label_intcont; ?></td>
				<td><input name="add_client_form_intcont" id="add_client_form_intcont" class="txtfield" value="<?php echo $add_client_data_intcont; ?>" /></td>
			  </tr>
			  <tr>
				<td colspan="2">
					<div align="right">
						<input type="submit" name="Submit" value="<?php if ($_GET['do']=='edit' || isset($_POST['edit_who'])) { echo $edit_client_form_submit; } else { echo $add_client_form_submit; } ?>" class="boton" />
					</div>
					<?php if ($_GET['do']!='edit' && empty($_POST['edit_who'])) { ?>
					<div class="message message_info">
						<p><?php echo $add_client_mail_info; ?></p>
					</div>
					<?php } ?>
				</td>
			</tr>
		  </table>
	
		</form>

	<?php } ?>
		
	</div>
		
</div>
	
<?php
	$database->Close();
	include('footer.php');
?>