<?php include('header.php'); ?>

	<div id="main">
		<h2><?php echo $ticonfile; ?></h2>

<?php

$database->MySQLDB();

// database vars
$filename = mysql_real_escape_string($_POST['name']);
$client_user = mysql_real_escape_string($_POST['clientname']);
$description = mysql_real_escape_string($_POST['description']);
$thefile = mysql_real_escape_string($_FILES['ufile']['name']);

$folder = 'upload/' . $client_user . '/';
$file_final_name= time().'-'.$thefile;
$path= $folder.$file_final_name;

// create MySQL entry
$timestampdate = time();
$result = $database->query("INSERT INTO tbl_files (id,url,filename,description,client_user,timestamp)"
."VALUES ('NULL', '$file_final_name', '$filename', '$description', '$client_user', '$timestampdate')");

// upload the file
if($thefile!=none)
{
	if(move_uploaded_file($_FILES['ufile']['tmp_name'], $path))
	{
	// start uploading ok message
?>
		<div id="txthome">
	
			<div class="message message_ok">
				<p><?php echo $up_ok; ?></p>
			</div>
			<p><strong><?php echo $up_filename; ?></strong> <?php echo $_FILES['ufile']['name']; ?><br />
			<strong><?php echo $up_filetype; ?></strong> <?php echo $_FILES['ufile']['type']; ?></p>
			
			<?php
				$total = $_FILES['ufile']['size'];
				getfilesize($total);
			?>
	
			<div id="linkcliente">
				<p><a href="upload/<?php echo $client_user; ?>/"><?php echo $client_link; ?> <strong><?php echo $client_user; ?></strong></a></p>
			</div>

			<?php
				// check if user wants to receive mail notifications
				$sql = $database->query('SELECT * FROM tbl_clients WHERE client_user="'.$client_user.'"');
				while($row = mysql_fetch_array($sql)) {
					if ($row['notify'] == '1') {

						$notify_email_link = $baseuri.'upload/'.$client_user.'/';
						$final_email_body = wordwrap($notify_email_body.$notify_email_link.$notify_email_body2,70);

						$success = mail($row['email'], $notify_email_subject, $final_email_body, "From:&lt;$admin_email_address&gt;\r\nReply-to:&lt;$admin_email_address&gt;\r\nContent-type: text/html; charset=us-ascii");

						if ($success){
						  echo '<div class="message message_ok"><p>'.$notify_email_ok.'</p></div>';
						}
						else{
						  echo '<div class="message message_error"><p>'.$notify_email_error.'</p></div>';
						}
					}
				}
			?>

		</div>

<?php
// end uploading ok message

}
else
{

// Upload error: show page
?>
		<div id="txthome">
			<div class="message message_error">
				<p><?php echo $up_error ?></p>
			</div>
		</div>
	</div>
<?php
// Error page done

} ?> 

	</div>

<?php $database->Close(); ?>

<?php include('footer.php'); // footer for both pages
}
?>