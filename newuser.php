<?php include('header.php'); ?>

<?php
if ($_POST) {
	// Variables para la base de datos
	$add_user_form_name = $_POST['add_user_form_name'];
	$add_user_form_user = $_POST['add_user_form_user'];
	$add_user_form_pass = md5($_POST['add_user_form_pass']);
	$add_user_form_email = $_POST['add_user_form_email'];
	$add_user_form_level = $_POST['add_user_form_level'];
	
	$database->MySQLDB();
	
	if (mysql_num_rows(mysql_query("SELECT * FROM tbl_users WHERE user = '$add_user_form_user'"))){
		  print "<meta http-equiv=\"refresh\" content=\"0;URL=newuser.php?stat=err2\">";
	}
	else
	{
		$timestampdate = time();
		$success = mysql_query("INSERT INTO tbl_users (id,user,password,name,email,level,timestamp)"
		."VALUES ('NULL', '$add_user_form_user', '$add_user_form_pass', '$add_user_form_name', '$add_user_form_email','$add_user_form_level', '$timestampdate')");
		
		$database->Close();
		
		if ($success){
		  print "<meta http-equiv=\"refresh\" content=\"0;URL=newuser.php?stat=ok\">";
		}
		else{
		  print "<meta http-equiv=\"refresh\" content=\"0;URL=newuser.php?stat=err\">";
		}
	}
} else { // do if just entering (no form info sent) ?>

	<div id="main">
		<h2><?php echo $add_utitle; ?></h2>
	
	<?php
	if ($_GET['stat'] == "ok") {
		 echo $add_user_ok;
	}
	
	else if ($_GET['stat'] == "err") {
		echo $add_user_error;
	}
	
	else if ($_GET['stat'] == "err2") {
		echo $add_user_exists;
	}
	
	else {
	?>
	
	<script type="text/javascript">
		var add_user_form_name = "<?php echo $add_ualrt_1; ?>"
		var add_user_form_user = "<?php echo $add_ualrt_2; ?>"
		var add_user_form_pass = "<?php echo $add_ualrt_3; ?>"
		var add_user_form_pass2 = "<?php echo $add_ualrt_4; ?>"
		var add_user_form_email = "<?php echo $add_ualrt_5; ?>"
		var invalid_mail = "<?php echo $install_invalid_mail; ?>"
		var alphaerror = "<?php echo $alphaerror; ?>"
		var pass_mismatch = "<?php echo $install_pass_mismatch; ?>"
		var pass_short = "<?php echo $install_pass_short; ?>"
		var pass_chars = "<?php echo $install_pass_chars; ?>"
		var create_user_chars = "<?php echo $create_user_chars; ?>"
		var create_user_length = "<?php echo $create_user_length; ?>"
	
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
				alert(pass_short)
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
	
		<div class="whiteform whitebox">
		
		<form action="" name="adduser" method="post" target="_self">
	
			<table border="0" cellspacing="1" cellpadding="1">
			  <tr>
				<td width="40%"><?php echo $add_user_form_name; ?></td>
				<td><input name="add_user_form_name" id="add_user_form_name" class="txtfield" value="<?php if ($query) { echo $row["name"] ;} ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_user_form_user; ?></td>
				<td><input name="add_user_form_user" id="add_user_form_user" maxlength="16" class="txtfield" maxlength="12" /></td>
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
				<td><input name="add_user_form_email" id="add_user_form_email" class="txtfield" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_user_form_level; ?></td>
				<td>
					<select name="add_user_form_level" id="add_user_form_level" class="txtfield">
						<option value="9"><?php echo $user_role_lvl9; ?></option>
						<option value="8"><?php echo $user_role_lvl8; ?></option>
						<option value="7"><?php echo $user_role_lvl7; ?></option>
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
		
		</div>
		
	<?php } ?>

</div>

<?php include('footer.php'); ?>

<?php } ?>