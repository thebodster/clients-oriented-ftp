<?php
$allowed_levels = array(9,8,7);
require_once('includes/includes.php');
$page_title = __('Upload file', 'cftp_admin');
include('header.php');

$database->MySQLDB();

// email texts
$notify_email_subject = __('New file uploaded for you','cftp_admin');
$notify_email_body = __('A new file has been uploaded for you to download.','cftp_admin');

$notify_email_finfo = __('File information:','cftp_admin');
$notify_email_fname = __('Name:','cftp_admin');
$notify_email_fdesc = __('Description:','cftp_admin');

$notify_email_body2 = __("If you don't want to be notified about new files, please contact the uploader.",'cftp_admin');
$notify_email_body3 = __('You can access a list of all your files','cftp_admin');
$notify_email_body4 = __('by logging in here','cftp_admin');

?>
<script src="includes/js/js.validations.php" type="text/javascript"></script>

<script type="text/javascript">

	window.onload = default_field;

	var js_err_name = "<?php echo $validation_no_filename; ?>"
	//var js_err_desc = "<?php echo $validation_no_description; ?>"
	var js_err_file = "<?php echo $validation_no_file; ?>"
	var js_err_client = "<?php echo $validation_no_client; ?>"

	function validateform(theform){
		is_complete(theform.name,js_err_name);
		//is_complete(theform.description,js_err_desc);
		is_complete(theform.clientname,js_err_client);
		is_complete(theform.ufile,js_err_file);
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
		$sql = $database->query("SELECT * FROM tbl_clients");
		$count = mysql_num_rows($sql);
		if (!$count) {
	?>
			<p><?php _e('There are no clients at the moment', 'cftp_admin'); ?></p>
			<p><a href="clientform.php" target="_self"><?php _e('Create a new one', 'cftp_admin'); ?></a> <?php _e('to be able to upload files for that account.', 'cftp_admin'); ?></p>
	<?php
		}
		else { 

			if($_POST) {

				$filename = mysql_real_escape_string($_POST['name']);
				$description = mysql_real_escape_string($_POST['description']);
				$client_user = mysql_real_escape_string($_POST['clientname']);
				$thefile = mysql_real_escape_string($_FILES['ufile']['name']);

				require_once('includes/classes/form-validation.php');

				$valid_me->validate('completed',$filename,$validation_no_filename);
				//$valid_me->validate('completed',$description,$validation_no_description);
				$valid_me->validate('completed',$thefile,$validation_no_file);
				$valid_me->validate('completed',$client_user,$validation_no_client);

				$valid_me->list_errors(); // if the form was submited with errors, show them here
			
				if ($valid_me->return_val) { //validation ok. continue to upload
					
					$this_admin = get_current_user_username(); // who is uploading this file?
			
					require_once('includes/classes/file-upload.php');
					$folder = 'upload/' . $client_user . '/';
					$upload_arguments = array(
											'folder' => $folder,
											'client' => $client_user,
											'uploader' => $this_admin,
											'file' => $_FILES['ufile'],
											'name' => $filename,
											'description' => $description
										);
					$this_upload = new Upload_File();
					$upload_state = $this_upload->upload($upload_arguments);


					if (isset($upload_state)) {
						switch ($upload_state) {
							case 'ok':
								echo system_message('ok',$file_upload_ok);
								// check if user wants to receive mail notifications
								$sql = $database->query('SELECT * FROM tbl_clients WHERE client_user="'.$client_user.'"');
								while($row = mysql_fetch_array($sql)) {
									if ($row['notify'] == '1') {
		
										// prepare email using the template
										$email_body = file_get_contents('emails/newfile.php');
						
										$email_body = str_replace('%BODY1%',$notify_email_body,$email_body);
										$email_body = str_replace('%FILE_INFO%',$notify_email_finfo,$email_body);
										$email_body = str_replace('%LABEL_NAME%',$notify_email_fname,$email_body);
										$email_body = str_replace('%FILE_NAME%',$filename,$email_body);
										$email_body = str_replace('%LABEL_DESCRIPTION%',$notify_email_fdesc,$email_body);
										$email_body = str_replace('%FILE_DESCRIPTION%',$description,$email_body);
										$email_body = str_replace('%BODY2%',$notify_email_body2,$email_body);
										$email_body = str_replace('%BODY3%',$notify_email_body3,$email_body);
										$email_body = str_replace('%BODY4%',$notify_email_body4,$email_body);
										$email_body = str_replace('%LINK%',$baseuri,$email_body);
										$email_body = str_replace('%SUBJECT%',$notify_email_subject,$email_body);
										
										$headers = 'From: '.$this_install_title.' <'.$admin_email_address.'>' . "\n";
										$headers .= 'Return-Path:<'.$admin_email_address.'>\r\n';
										$headers .= 'MIME-Version: 1.0' . "\n";
										$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
										$success = @mail($row['email'], $notify_email_subject, $email_body, $headers);
										if ($success){
											$msg = __('Your client was notified about the file','cftp_admin');
											echo system_message('ok',$msg);
										}
										else{
											$msg = __("E-mail notify couldn't be sent",'cftp_admin');
											echo system_message('error',$msg);
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
								echo system_message('error',$msg);
							break;
							case 'err':
								$msg = __('Error sending file. Please try again.','cftp_admin');
								echo system_message('error',$msg);
							break;
							case 'err_type':
								$msg = __('This filetype is not allowed. Please check the options page and change it accordingly.<br /><strong>Warning</strong>: This could break security.','cftp_admin');
								echo system_message('error',$msg);
							break;
							case 'err_exist':
								$msg = __("The file doesn't exist anymore, or it's empty. You cannot upload 0kb files.",'cftp_admin');
								echo system_message('error',$msg);
							break;
						}
					}

				}
				else {
					$show_upload_form = 1;
				}
			}
			else {
				$show_upload_form = 1;
		?>

	<?php
		}
	} // end if for users count
	?>

	<?php
		if($show_upload_form === 1) {
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
			$msg = __('Maximum allowed file size: ','cftp_admin');
			$msg .= MAX_FILESIZE.' mb.';
			echo system_message('info',$msg);

		} // Close show form
	?>

	</div>
</div>

<?php
	$database->Close();
	include('footer.php');
?>