<?php
$allowed_levels = array(9,8,7);
require_once('includes/includes.php');
$page_title = $page_title_upload;
include('header.php');

$database->MySQLDB();

$filename = mysql_real_escape_string($_POST['name']);
$description = mysql_real_escape_string($_POST['description']);
$client_user = mysql_real_escape_string($_POST['clientname']);
$thefile = mysql_real_escape_string($_FILES['ufile']['name']);

require_once('includes/form_validation_class.php');

if ($_POST) {
	
	// begin form validation
	$valid_me->validate('completed',$filename,$validation_no_filename);
	$valid_me->validate('completed',$description,$validation_no_description);
	$valid_me->validate('completed',$thefile,$validation_no_file);
	$valid_me->validate('completed',$client_user,$validation_no_client);

	if ($valid_me->return_val) { //validation ok. continue to upload

		// upload checkings
		if(is_uploaded_file($_FILES['ufile']['tmp_name'])) {
			if ($_FILES['ufile']['size'] > 0) {
				// check for allowed file types
				$allowed_files = "/^\.(".$allowed_file_types."){1}$/i";
				//fix the filename
				$safe_filename = preg_replace(array("/\s+/", "/[^-\.\w]+/"), array("_", ""), trim($_FILES['ufile']['name']));
				if (preg_match($allowed_files, strrchr($safe_filename, '.'))) {
					// make the final filename using timestamp+sanitized name			
					$folder = 'upload/' . $client_user . '/';
					$file_final_name= time().'-'.$safe_filename;
					$path= $folder.$file_final_name;
					// try to upload
					if (move_uploaded_file($_FILES['ufile']['tmp_name'], $path)) {
						// create MySQL entry if the file was uploaded correctly
						$timestampdate = time();
						$result = $database->query("INSERT INTO tbl_files (id,url,filename,description,client_user,timestamp)"
						."VALUES ('NULL', '$file_final_name', '$filename', '$description', '$client_user', '$timestampdate')");
						$upload_state = 'ok';
					}
					else {
						// could not move file
						$upload_state = 'err_move';
					}
				}
				else {
					// filetype isn't allowed
					$upload_state = 'err_type';
				}
			}
			else {
				// file doesn't exist anymore
				$upload_state = 'err_exist';
			}
		}
		// no file was selected
		else {
			$upload_state = 'err';
		}

	}

} // do if just entering (no form info sent)

include_once('includes/js/js.validations.php'); ?>

<script type="text/javascript">

	window.onload = default_field;

	var js_err_name = "<?php echo $validation_no_filename; ?>"
	var js_err_desc = "<?php echo $validation_no_description; ?>"
	var js_err_file = "<?php echo $validation_no_file; ?>"
	var js_err_client = "<?php echo $validation_no_client; ?>"

	function validateform(theform){
		is_complete(theform.name,js_err_name);
		is_complete(theform.description,js_err_desc);
		is_complete(theform.clientname,js_err_file);
		is_complete(theform.ufile,js_err_client);
		// show the errors or continue if everything is ok
		if (error_list != '') {
			alert(error_title+error_list)
			error_list = '';
			return false;
		}
	}

</script>

<div id="main">
	<h2><?php echo $page_title; ?></h2>
	
	<div class="whiteform whitebox">
	
	<?php
		// count clients to show error or form
		$database->MySQLDB();

		$sql = $database->query("SELECT * FROM tbl_clients");
		$count=mysql_num_rows($sql);
		if (!$count) {
			echo $upload_no_clients;
		}
		else { 

			$valid_me->list_errors(); // if the form was submited with errors, show them here

			if (isset($upload_state)) {
				switch ($upload_state) {
					case 'ok':
						echo '<div class="message message_ok"><p>'.$file_upload_ok.'</p></div>';
						// check if user wants to receive mail notifications
						$sql = $database->query('SELECT * FROM tbl_clients WHERE client_user="'.$client_user.'"');
						while($row = mysql_fetch_array($sql)) {
							if ($row['notify'] == '1') {
								$notify_email_link = $baseuri.'upload/'.$client_user.'/';
								$final_email_body = wordwrap($notify_email_body.$notify_email_link.$notify_email_body2,70);
								$success = mail($row['email'], $notify_email_subject, $final_email_body, "From:<$admin_email_address>\r\nReply-to:<$admin_email_address>\r\nContent-type: text/html; charset=us-ascii");
								if ($success){
								  echo '<div class="message message_ok"><p>'.$notify_email_ok.'</p></div>';
								}
								else{
								  echo '<div class="message message_error"><p>'.$notify_email_error.'</p></div>';
								}
							}
						}
						// end notification
						?>
						<p><strong><?php echo $up_filename; ?></strong> <?php echo $_FILES['ufile']['name']; ?><br />
						<strong><?php echo $up_filetype; ?></strong> <?php echo $_FILES['ufile']['type']; ?></p>
						
						<?php $total = $_FILES['ufile']['size']; getfilesize($total); ?>
				
						<div id="linkcliente">
							<?php
							$sql2 = $database->query('SELECT * from tbl_clients where client_user="' . $client_user .'"');
							while ($row = mysql_fetch_array($sql2)) {
								$user_full_name = $row['name'];
							}
							?>
							<p><a href="upload/<?php echo $client_user; ?>/"><?php echo $client_link; ?> <strong><?php echo $user_full_name; ?></strong></a></p>
						</div><?php
					break;
					case 'err_move':
						echo '<div class="message message_error"><p>'.$file_upload_move.'</p></div>';
					break;
					case 'err':
						echo '<div class="message message_error"><p>'.$file_upload_error.'</p></div>';
					break;
					case 'err_type':
						echo '<div class="message message_error"><p>'.$file_upload_types_error.'</p></div>';
					break;
					case 'err_exist':
						echo '<div class="message message_error"><p>'.$file_upload_exist_error.'</p></div>';
					break;
				}
			}
			else {
		?>
	
	<form action="fileupload.php" name="uploadf" method="post" enctype="multipart/form-data" onsubmit="return validateform(this);">

		<input type="hidden" name="MAX_FILE_SIZE" value="1048576000">
		<table border="0" cellspacing="1" cellpadding="1">
		  <tr>
			<td width="40%"><?php echo $upfname; ?></td>
			<td><input type="text" name="name" id="name" class="txtfield" value="<?php echo $filename; ?>" /></td>
		  </tr>
		  <tr>
			<td><?php echo $upfdes; ?></td>
			<td><textarea name="description" id="description" class="txtfield"><?php echo $_POST['description']; ?></textarea></td>
		  </tr>
		  <tr>
			<td><?php echo $upffile; ?></td>
			<td><input name="ufile" type="file" id="ufile" size="32" class="txtfield" /></td>
		  </tr>
		  <tr>
			<td><?php echo $upclient; ?></td>
			<td><select name="clientname" id="clientname" class="txtfield" >
					<?php
						$sql = $database->query("SELECT client_user, name FROM tbl_clients");
						while($row = mysql_fetch_array($sql)) {
						?>
							<option value="<?php echo $row['client_user']; ?>" <?php if($client_user==$row['client_user']){?>selected="selected"<?php } ?> ><?php echo $row['name']; ?></option>
						<?php
						}
					?>
				</select>
			</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>
				<div align="right"><input type="submit" name="Submit" value="<?php echo $upload_submit; ?>" class="boton" /></div>
			</td>
		  </tr>
	  </table>

	</form>
	
	<?php
		}
	} // end if for users count
	?>

	</div>
</div>

<?php
	$database->Close();
	include('footer.php');
?>