<?php
$allowed_levels = array(9,8,7);
require_once('includes/includes.php');
$page_title = __('Upload file', 'cftp_admin');
include('header.php');

$database->MySQLDB();

$filename = mysql_real_escape_string($_POST['name']);
$description = mysql_real_escape_string($_POST['description']);
$client_user = mysql_real_escape_string($_POST['clientname']);
$thefile = mysql_real_escape_string($_FILES['ufile']['name']);

require_once('includes/form_validation_class.php');

// email texts
$notify_email_subject = __('New file uploaded for you','cftp_admin');
$notify_email_body = __('A new file has been uploaded for you to download.','cftp_admin');
$notify_email_body2 = __("If you don't want to be notified about new files, please contact the uploader.",'cftp_admin');
$notify_email_body3 = __('You can access a list of all your files','cftp_admin');
$notify_email_body4 = __('by logging in here','cftp_admin');

if ($_POST) {
	
	// begin form validation
	$valid_me->validate('completed',$filename,$validation_no_filename);
	$valid_me->validate('completed',$description,$validation_no_description);
	$valid_me->validate('completed',$thefile,$validation_no_file);
	$valid_me->validate('completed',$client_user,$validation_no_client);

	if ($valid_me->return_val) { //validation ok. continue to upload

	// who is uploading this file?
	if (isset($_COOKIE['loggedin'])) {
		$this_admin = $_COOKIE['loggedin'];
	}
	elseif (isset($_SESSION['loggedin'])) {
		$this_admin = $_SESSION['loggedin'];
	}

		// upload checkings
		if(is_uploaded_file($_FILES['ufile']['tmp_name'])) {
			if ($_FILES['ufile']['size'] > 0) {
				// check for allowed file types
				$allowed_files = "/^\.(".$allowed_file_types."){1}$/i";
				//fix the filename
				$safe_filename = preg_replace(array("/\s+/", "/[^-\.\w]+/"), array("-", ""), trim($_FILES['ufile']['name']));
				if (preg_match($allowed_files, strrchr($safe_filename, '.'))) {
					// make the final filename using timestamp+sanitized name			
					$folder = 'upload/' . $client_user . '/';
					$file_final_name= time().'-'.$safe_filename;
					$path= $folder.$file_final_name;
					// try to upload
					if (move_uploaded_file($_FILES['ufile']['tmp_name'], $path)) {
						// create MySQL entry if the file was uploaded correctly
						$timestampdate = time();
						$result = $database->query("INSERT INTO tbl_files (id,url,filename,description,client_user,timestamp,uploader)"
						."VALUES ('NULL', '$file_final_name', '$filename', '$description', '$client_user', '$timestampdate', '$this_admin')");
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
?>
<script src="includes/js/js.validations.php" type="text/javascript"></script>

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
	?>
			<p><?php _e('There are no clients at the moment', 'cftp_admin'); ?></p>
			<p><a href="clientform.php" target="_self"><?php _e('Create a new one', 'cftp_admin'); ?></a> <?php _e('to be able to upload files for that account.', 'cftp_admin'); ?></p>
	<?php
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

								// prepare email using the template
								$email_body = file_get_contents('emails/newfile.php');
				
								$email_body = str_replace('%BODY1%',$notify_email_body,$email_body);
								$email_body = str_replace('%BODY2%',$notify_email_body2,$email_body);
								$email_body = str_replace('%BODY3%',$notify_email_body3,$email_body);
								$email_body = str_replace('%BODY4%',$notify_email_body4,$email_body);
								$email_body = str_replace('%LINK%',$baseuri,$email_body);
								$email_body = str_replace('%SUBJECT%',$notify_email_subject,$email_body);
								
								$success = @mail($row['email'], $notify_email_subject, $email_body, "From:<$admin_email_address>\r\nReply-to:<$admin_email_address>\r\nContent-type: text/html; charset=us-ascii");
								if ($success){
								  echo '<div class="message message_ok"><p>';
								  _e('Your client was notified about the file','cftp_admin');
								  echo '</p></div>';
								}
								else{
								  echo '<div class="message message_error"><p>';
								  _e("E-mail notify couldn't be sent",'cftp_admin');
								  echo '</p></div>';
								}
							}
						}
						// end notification
						?>
						<p><strong><?php _e('File name:','cftp_admin'); ?></strong> <?php echo $_FILES['ufile']['name']; ?><br />
						<strong><?php _e('File type','cftp_admin'); ?></strong> <?php echo $_FILES['ufile']['type']; ?><br />
						<strong><?php _e('File size:','cftp_admin'); ?></strong> <?php $total = $_FILES['ufile']['size']; getfilesize($total); ?></p>
				
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
						$msg = __('Error moving uploaded file. Please try again.','cftp_admin');
						echo '<div class="message message_error"><p>'.$msg.'</p></div>';
					break;
					case 'err':
						$msg = __('Error sending file. Please try again.','cftp_admin');
						echo '<div class="message message_error"><p>'.$msg.'</p></div>';
					break;
					case 'err_type':
						$msg = __('This filetype is not allowed. Please check the options page and change it accordingly.<br /><strong>Warning</strong>: This could break security.','cftp_admin');
						echo '<div class="message message_error"><p>'.$msg.'</p></div>';
					break;
					case 'err_exist':
						$msg = __("The file doesn't exist anymore, or it's empty. You cannot upload 0kb files.",'cftp_admin');
						echo '<div class="message message_error"><p>'.$msg.'</p></div>';
					break;
				}
			}
			else {
		?>

	<form action="fileupload.php" name="uploadf" method="post" enctype="multipart/form-data" onsubmit="return validateform(this);">

		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILESIZE*1050000; ?>" />
		<table border="0" cellspacing="1" cellpadding="1">
		  <tr>
			<td width="40%"><?php _e('Name','cftp_admin'); ?></td>
			<td><input type="text" name="name" id="name" class="txtfield" value="<?php echo $filename; ?>" /></td>
		  </tr>
		  <tr>
			<td><?php _e('File description','cftp_admin'); ?></td>
			<td><textarea name="description" id="description" class="txtfield"><?php echo $_POST['description']; ?></textarea></td>
		  </tr>
		  <tr>
			<td><?php _e('Select file','cftp_admin'); ?></td>
			<td><input name="ufile" type="file" id="ufile" size="32" class="txtfield" /></td>
		  </tr>
		  <tr>
			<td><?php _e('Upload for','cftp_admin'); ?></td>
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
				<div align="right"><input type="submit" name="Submit" value="<?php _e('Upload','cftp_admin'); ?>" class="boton" /></div>
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