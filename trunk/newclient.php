<?php include('header.php'); ?>

<?php
if ($_POST) {

	$database->MySQLDB();

	$add_cname = mysql_real_escape_string($_POST['add_cname']);
	$add_cuser = mysql_real_escape_string($_POST['add_cuser']);
	$add_cpass = mysql_real_escape_string(md5($_POST['add_cpass']));
	$add_cadd = mysql_real_escape_string($_POST['add_cadd']);
	$add_cphone = mysql_real_escape_string($_POST['add_cphone']);
	$add_cmail = mysql_real_escape_string($_POST['add_cmail']);
	$add_ccont = mysql_real_escape_string($_POST['add_ccont']);
	if(isset($_POST["add_cnoti"])) { $add_cnoti = 1; } else { $add_cnoti = 0; }
	
	if(mysql_num_rows(mysql_query("SELECT * FROM tbl_clients WHERE client_user = '$add_cuser'"))){
		header("location:newclient.php?stat=err2");
	}
	else
	{
		$timestampdate = time();
		$success = mysql_query("INSERT INTO tbl_clients (id,name,client_user,password,address,phone,email,notify,contact,timestamp)"
		."VALUES ('NULL', '$add_cname', '$add_cuser', '$add_cpass', '$add_cadd', '$add_cphone', '$add_cmail', '$add_cnoti', '$add_ccont', '$timestampdate')");
		
		$database->Close();
		
		// Create user folder
		$folder = 'upload/' . $add_cuser . '/';
		if (!file_exists($folder)) {
			mkdir($folder); chmod($folder, 0755);
			$folder2 = 'upload/' . $add_cuser . '/thumbs/';
			mkdir($folder2); chmod($folder2, 0755);

		// Create index.php on clients folder
			$index_content = '$this_user = "' . $add_cuser . '" ; include_once(\'../../templates/default/template.php\');';
			$addwhat = '<?php ' . $index_content . ' ?>';
		
			$file = $folder . "index.php";   
			if (!$file_handle = fopen($file,"a")) { echo $creat_err1; }
			if (!fwrite($file_handle, $addwhat)) { echo $creat_err2; }
			fclose($file_handle);
			$linkcli = realpath($file);
		}
		
		if ($success){
			header("location:newclient.php?stat=ok");
		}
		else{
			header("location:newclient.php?stat=err");
		}
	}
} else { // do if just entering (no form info sent) ?>

	<div id="main">
		<h2><?php echo $add_ctitle; ?></h2>
	
	
	<?php
	if ($_GET['stat'] == "ok") {
		 echo $add_client_ok;
	}
	
	else if ($_GET['stat'] == "err") {
		echo $add_client_error;
	}
	
	else if ($_GET['stat'] == "err2") {
		echo $add_client_exists;
	}
	
	else {
	?>
	
	<script type="text/javascript">
		var add_cname = "<?php echo $add_alrt_1; ?>"
		var add_user = "<?php echo $add_alrt_2; ?>"
		var add_pass = "<?php echo $add_alrt_3; ?>"
		var add_email = "<?php echo $add_alrt_4; ?>"
		var alphaerror = "<?php echo $alphaerror; ?>"
		var invalid_mail = "<?php echo $install_invalid_mail; ?>"
		var pass_mismatch = "<?php echo $install_pass_mismatch; ?>"
		var pass_short = "<?php echo $install_pass_short; ?>"
		var pass_chars = "<?php echo $install_pass_chars; ?>"
		var create_user_chars = "<?php echo $create_user_chars; ?>"
		var create_user_length = "<?php echo $create_user_length; ?>"

		function validateme(){
	
			if (document.addclient.add_cname.value.length==0) {
				alert(add_cname)
				return false;
			}
			
			if (document.addclient.add_cuser.value.length==0) {
				alert(add_user)
				return false;
			}
		
			if (document.addclient.add_cpass.value.length==0) {
				alert(add_pass)
				return false;
			}
		
			if (document.addclient.add_cmail.value.length==0) {
				alert(add_email)
				return false;
			}

			// onto email validation now
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			var address = document.addclient.add_cmail.value;
			if (reg.test(address) == false) {
				alert(invalid_mail);
				return false;
			}

			// short or long passwd
			if (document.addclient.add_cpass.value.length < 6 || document.addclient.add_cpass.value.length > 12) {
				alert(pass_short)
				return false;
			}
			
			// short or long username
			if (document.addclient.add_cuser.value.length < 6 || document.addclient.add_cuser.value.length > 12) {
				alert(create_user_length)
				return false;
			}
			
			// alphanumeric check for user
			var numaric = document.addclient.add_cuser.value;
			if (!(numaric.match(/^[a-zA-Z0-9]+$/)))
			  {
				alert(create_user_chars);
				return false;
			}
			
			// alphanumeric check for password
			var numaric = document.addclient.add_cpass.value;
			if (!(numaric.match(/^[a-zA-Z0-9]+$/)))
			  {
				alert(pass_chars);
				return false;
			}
			
			// password matching validation
			if (document.addclient.add_cpass.value != document.addclient.add_cpass2.value) {
				alert(pass_mismatch)
				return false;
			}

		document.addclient.submit();

		}
	</script>
	
		<div class="whiteform whitebox">
		
		<form action="" name="addclient" method="post" target="_self">
	
			<table border="0" cellspacing="1" cellpadding="1">
			  <tr>
				<td width="40%"><?php echo $add_cname; ?></td>
				<td><input name="add_cname" id="add_cname" class="txtfield" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_cuser; ?></td>
				<td><input name="add_cuser" id="add_cuser" class="txtfield" maxlength="12" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_cpass; ?></td>
				<td><input name="add_cpass" id="add_cpass" class="txtfield" type="password" maxlength="12" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_cpass2; ?></td>
				<td><input name="add_cpass2" id="add_cpass2" class="txtfield" type="password" maxlength="12" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_cadd; ?></td>
				<td><input name="add_cadd" id="add_cadd" class="txtfield" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_cphone; ?></td>
				<td><input name="add_cphone" id="add_cphone" class="txtfield" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_cmail; ?></td>
				<td><input name="add_cmail" id="add_cmail" class="txtfield" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_cnoti; ?></td>
				<td><input type="checkbox" name="add_cnoti" id="add_cnoti" /></td>
			  </tr>
			  <tr>
				<td><?php echo $add_ccont; ?></td>
				<td><input name="add_ccont" id="add_ccont" class="txtfield" /></td>
			  </tr>
			  <tr>
				<td colspan="2">
					<div align="right">
						<input type="button" name="Submit" value="<?php echo $add_client_submit; ?>" class="boton" onclick="validateme();" />
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