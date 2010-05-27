<?php
require_once('includes/vars.php');
$allowed_levels = array(9);
$page_title = $page_title_newuser;
include('header.php');

$database->MySQLDB();

$add_user_data_name = mysql_real_escape_string($_POST['add_user_form_name']);
$add_user_data_user = mysql_real_escape_string($_POST['add_user_form_user']);
$add_user_data_pass = md5(mysql_real_escape_string($_POST['add_user_form_pass']));
$add_user_data_pass2 = mysql_real_escape_string(md5($_POST['add_user_form_pass2']));
$add_user_data_email = mysql_real_escape_string($_POST['add_user_form_email']);
$add_user_data_level = mysql_real_escape_string($_POST['add_user_form_level']);

require_once('includes/form_validation_class.php');

if ($_POST) {

	// begin form validation
	$valid_me->validate('completed',$add_user_data_name,$validation_no_name);
	$valid_me->validate('completed',$add_user_data_user,$validation_no_user);
	$valid_me->validate('completed',$_POST['add_user_form_pass'],$validation_no_pass);
	$valid_me->validate('completed',$add_user_data_email,$validation_no_email);
	$valid_me->validate('completed',$add_user_data_level,$validation_no_level); // just a precaution
	$valid_me->validate('email',$add_user_data_email,$validation_invalid_mail);
	$valid_me->validate('alpha',$add_user_data_user,$validation_alpha_user);
	$valid_me->validate('alpha',$_POST['add_user_form_pass'],$validation_alpha_pass);
	$valid_me->validate('length',$add_user_data_user,$validation_length_user,MIN_USER_CHARS,MAX_USER_CHARS);
	$valid_me->validate('length',$_POST['add_user_form_pass'],$validation_length_pass,MIN_PASS_CHARS,MAX_PASS_CHARS);
	$valid_me->validate('pass_match','',$validation_match_pass,'','',$_POST['add_user_form_pass'],$_POST['add_user_form_pass2']);
	$valid_me->validate('user_exists',$add_user_data_user,$add_user_exists,'','','','','tbl_users','user');
	$valid_me->validate('user_exists',$add_user_data_email,$add_user_mail_exists,'','','','','tbl_users','email');
	
	if ($valid_me->return_val) { //lets continue

		// add new user to DB
		$timestampdate = time();
		$success = mysql_query("INSERT INTO tbl_users (id,user,password,name,email,level,timestamp)"
		."VALUES ('NULL', '$add_user_data_user', '$add_user_data_pass', '$add_user_data_name', '$add_user_data_email','$add_user_data_level', '$timestampdate')");
		
		if ($success){
			$query_state = 'ok';
		}
		else {
			$query_state = 'err';
		}

	} //validation ends here
	

} // do if just entering (no form info sent) ?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>
	
		<div class="whiteform whitebox">
		
		<?php $valid_me->list_errors(); // if the form was submited with errors, show them here ?>
		
		<?php
			if ($query_state == 'ok') {
				 echo '<div class="message message_ok"><p>'.$add_user_ok.'</p></div>';
			}
			else if ($query_state == 'err') {
				echo '<div class="message message_error"><p>'.$add_user_error.'</p></div>';
			}
			else {
		?>

	<?php include_once('includes/js/js.validations.php'); ?>

	<script type="text/javascript">
	
		window.onload = default_field;

		var js_err_name = "<?php echo $validation_no_name; ?>"
		var js_err_user = "<?php echo $validation_no_user; ?>"
		var js_err_pass = "<?php echo $validation_no_pass; ?>"
		var js_err_pass2 = "<?php echo $validation_no_pass2; ?>"
		var js_err_email = "<?php echo $validation_no_email; ?>"
		var js_err_level = "<?php echo $validation_no_level; ?>"
		var js_err_invalid_mail = "<?php echo $validation_invalid_mail; ?>"
		var js_err_pass_mismatch = "<?php echo $validation_match_pass; ?>"
		var js_err_user_length = "<?php echo $validation_length_user; ?>"
		var js_err_pass_length = "<?php echo $validation_length_pass; ?>"
		var je_err_pass_chars = "<?php echo $validation_alpha_pass; ?>"
		var js_err_user_chars = "<?php echo $validation_alpha_user; ?>"

		function validateform(theform){
			is_complete(theform.add_user_form_name,js_err_name);
			is_complete(theform.add_user_form_user,js_err_user);
			is_complete(theform.add_user_form_pass,js_err_pass);
			is_complete(theform.add_user_form_pass2,js_err_pass2);
			is_complete(theform.add_user_form_email,js_err_email);
			is_complete(theform.add_user_form_level,js_err_level);
			is_length(theform.add_user_form_user,<?php echo MIN_USER_CHARS; ?>,<?php echo MAX_USER_CHARS; ?>,js_err_user_length);
			is_length(theform.add_user_form_pass,<?php echo MIN_PASS_CHARS; ?>,<?php echo MAX_PASS_CHARS; ?>,js_err_pass_length);
			is_email(theform.add_user_form_email,js_err_invalid_mail);
			is_alpha(theform.add_user_form_user,js_err_user_chars);
			is_alpha(theform.add_user_form_pass,je_err_pass_chars);
			is_match(theform.add_user_form_pass,theform.add_user_form_pass2,js_err_pass_mismatch);
			// show the errors or continue if everything is ok
			if (error_list != '') {
				alert(error_title+error_list)
				error_list = '';
				return false;
			}
		}
	
	</script>

		<form action="newuser.php" name="adduser" method="post" onsubmit="return validateform(this);">
			<table border="0" cellspacing="1" cellpadding="1">
			  <tr>
				<td width="40%"><?php echo $add_user_form_name; ?></td>
				<td><input name="add_user_form_name" id="add_user_form_name" class="txtfield" value="<?php echo $add_user_data_name; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_user_form_user; ?></td>
				<td><input name="add_user_form_user" id="add_user_form_user" class="txtfield" maxlength="<?php echo MAX_USER_CHARS; ?>" value="<?php echo $add_user_data_user; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_user_form_pass; ?></td>
				<td><input name="add_user_form_pass" id="add_user_form_pass" class="txtfield" type="password" maxlength="<?php echo MAX_PASS_CHARS; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_user_form_pass2; ?></td>
				<td><input name="add_user_form_pass2" id="add_user_form_pass2" class="txtfield" type="password" maxlength="<?php echo MAX_PASS_CHARS; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_user_form_email; ?></td>
				<td><input name="add_user_form_email" id="add_user_form_email" class="txtfield" value="<?php echo $add_user_data_email; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_user_form_level; ?></td>
				<td>
					<select name="add_user_form_level" id="add_user_form_level" class="txtfield">
						<option value="9" <?php if( $add_user_data_level == '9') { echo 'selected="selected"'; } ?>><?php echo $user_role_lvl9; ?></option>
						<option value="8" <?php if( $add_user_data_level == '8') { echo 'selected="selected"'; } ?>><?php echo $user_role_lvl8; ?></option>
						<option value="7" <?php if( $add_user_data_level == '7') { echo 'selected="selected"'; } ?>><?php echo $user_role_lvl7; ?></option>
					</select>
				</td>
			  </tr>
			  <tr>
				<td colspan="2">
					<div align="right">
						<input type="submit" name="Submit" value="<?php echo $add_user_form_submit; ?>" class="boton" />
					</div>
				</td>
				</tr>
		  </table>
	
		</form>
		
		</div>

		<?php } ?>

</div>

<?php
	$database->Close();
	include('footer.php');
?>