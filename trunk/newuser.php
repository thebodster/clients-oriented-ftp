<?php include('header.php');

$database->MySQLDB();

$add_user_data_name = mysql_real_escape_string($_POST['add_user_form_name']);
$add_user_data_user = mysql_real_escape_string($_POST['add_user_form_user']);
$add_user_data_pass = mysql_real_escape_string(md5($_POST['add_user_form_pass']));
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
	$valid_me->validate('length',$add_user_data_user,$validation_length_user,6,12);
	$valid_me->validate('length',$_POST['add_user_form_pass'],$validation_length_pass,6,12);
	$valid_me->validate('pass_match','',$validation_match_pass,'','',$_POST['add_user_form_pass'],$_POST['add_user_form_pass2']);
	$valid_me->validate('user_exists',$add_user_data_user,$add_user_exists,'','','','','tbl_users','user');
	
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
	<h2><?php echo $add_utitle; ?></h2>
	
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

	<script type="text/javascript">
		var add_user_form_name = "<?php echo $validation_no_name; ?>"
		var add_user_form_user = "<?php echo $validation_no_user; ?>"
		var add_user_form_pass = "<?php echo $validation_no_pass; ?>"
		var add_user_form_pass2 = "<?php echo $validation_no_pass; ?>"
		var add_user_form_email = "<?php echo $validation_no_email; ?>"
		var invalid_mail = "<?php echo $install_invalid_mail; ?>"
		var pass_mismatch = "<?php echo $validation_match_pass; ?>"
		var create_user_length = "<?php echo $validation_length_user; ?>"
		var create_pass_short = "<?php echo $validation_length_pass; ?>"
		var pass_chars = "<?php echo $validation_alpha_pass; ?>"
		var create_user_chars = "<?php echo $validation_alpha_user; ?>"
		
	
		function validateme(){
	
			if (document.adduser.add_user_form_name.value.length==0) {
				alert(add_user_form_name)
				return false;
			}
			
			if (document.adduser.add_user_form_user.value.length==0) {
				alert(add_user_form_user)
				return false;
			}
		
			if (document.adduser.add_user_form_pass.value.length==0) {
				alert(add_user_form_pass)
				return false;
			}
		
			if (document.adduser.add_user_form_email.value.length==0) {
				alert(add_user_form_email)
				return false;
			}

			// onto email validation now
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			var address = document.adduser.add_user_form_email.value;
			if (reg.test(address) == false) {
				alert(invalid_mail);
				return false;
			}

			// short or long passwd
			if (document.adduser.add_user_form_pass.value.length < 6 || document.adduser.add_user_form_pass.value.length > 12) {
				alert(create_pass_short)
				return false;
			}
			
			// short or long username
			if (document.adduser.add_user_form_user.value.length < 6 || document.adduser.add_user_form_user.value.length > 12) {
				alert(create_user_length)
				return false;
			}
			
			// alphanumeric check for user
			var numaric = document.adduser.add_user_form_user.value;
			if (!(numaric.match(/^[a-zA-Z0-9]+$/)))
			  {
				alert(create_user_chars)
				return false;
			}
			
			// alphanumeric check for password
			var numeric = document.adduser.add_user_form_pass.value;
			if (!(numeric.match(/^[a-zA-Z0-9]+$/)))
			  {
				alert(pass_chars)
				return false;
			}
			
			// password matching validation
			if (document.adduser.add_user_form_pass.value != document.adduser.add_user_form_pass2.value) {
				alert(pass_mismatch)
				return false;
			}
			
		document.adduser.submit();
		}
	
	</script>

		<form action="newuser.php" name="adduser" method="post" target="_self">
			<table border="0" cellspacing="1" cellpadding="1">
			  <tr>
				<td width="40%"><?php echo $add_user_form_name; ?></td>
				<td><input name="add_user_form_name" id="add_user_form_name" class="txtfield" value="<?php echo $add_user_data_name; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_user_form_user; ?></td>
				<td><input name="add_user_form_user" id="add_user_form_user" class="txtfield" maxlength="12" value="<?php echo $add_user_data_user; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_user_form_pass; ?></td>
				<td><input name="add_user_form_pass" id="add_user_form_pass" class="txtfield" type="password" maxlength="12" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_user_form_pass2; ?></td>
				<td><input name="add_user_form_pass2" id="add_user_form_pass2" class="txtfield" type="password" maxlength="12" /></td>
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
						<input type="button" name="Submit" value="<?php echo $add_user_form_submit; ?>" class="boton" onclick="validateme();" />
					</div>
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