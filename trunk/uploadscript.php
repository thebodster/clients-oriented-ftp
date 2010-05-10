<?php include('header.php'); ?>

	<div id="main">
		<h2><?php echo $ticonfile; ?></h2>

<?php
// database vars
$filename = $_POST['name'];
$client_user = $_POST['clientname'];
$description = $_POST['description'];
$thefile = $_FILES['ufile']['name'];

$sqllink = mysql_connect($host, $dbuser, $dbpass)or die('Cant connect to database');
mysql_select_db($dbname)or die('Database not found');

// create MySQL entry
$timestampdate = time();
$result = mysql_query("INSERT INTO tbl_files (id,url,filename,description,client_user,timestamp)"
."VALUES ('NULL', '$thefile', '$filename', '$description', '$client_user', '$timestampdate')");

// upload the file
$folder = 'upload/' . $client_user . '/';
$path= $folder.$_FILES['ufile']['name'];
if($thefile!=none)
{
	// start uploading ok message
	if(copy($_FILES['ufile']['tmp_name'], $path))
	{
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
				$newsql = mysql_query('SELECT * FROM tbl_clients WHERE client_user="'.$client_user.'"');
				while($row = mysql_fetch_array($newsql)) {
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

<?php mysql_close($sqllink); ?>

<?php include('footer.php'); // footer for both pages
}
?>