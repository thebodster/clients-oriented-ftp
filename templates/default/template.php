<?php
/*
Template name:
Default
*/

$ld = 'cftp_template'; // specify the language domain for this template
include_once('../../templates/common.php'); // include the required functions for every template

$window_title = __('File downloads','cftp_template');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $user_full_name.' | '.$window_title; ?> | <?php echo $short_system_name; ?></title>
<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this_template; ?>main.css" />
<link rel="shortcut icon" href="../../favicon.ico" />
<link href='http://fonts.googleapis.com/css?family=Cabin+Condensed' rel='stylesheet' type='text/css'>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script src="../../includes/js/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="../../includes/js/jquery.tablesorter.pager.js" type="text/javascript"></script>
</head>

<body>

<div id="header">
	<h1><?php echo $full_system_name; ?></h1>
	<a href="../../process.php?do=logout" target="_self" id="logout"><?php _e('Logout', 'cftp_admin'); ?></a>
</div>

<div id="under_header">
	<div id="window_title"><?php echo '<strong>'.$user_full_name.'</strong> | '.$window_title; ?></div>
</div>

<div id="wrapper">

	<script type="text/javascript">
		$(document).ready(function()
			{
				$("#files_list")
					.tablesorter( {
						sortList: [[3,1]], widgets: ['zebra'], headers: {
							4: { sorter: false },
							5: { sorter: false },
							6: { sorter: false }
							<?php
								$clients_allowed = array(9,8,7);
								if (in_session_or_cookies($clients_allowed)) {
							?>
								,7: { sorter: false },
								8: { sorter: false }
							<?php } ?>
						}
				})
				.tablesorterPager({container: $("#pager")})
			}
		);
	
		function confirm_file_delete() {
			if (confirm("<?php _e('This will delete the file permanently. Continue?','cftp_template'); ?>")) return true ;
			else return false ;
		}

		var xhr;
		function startAjax() {
			if(window.XMLHttpRequest) {
				xhr = new XMLHttpRequest();
			}
			else if(window.ActiveXObject) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		
		function addDownloadCount(fileid) {
			startAjax();
			xhr.open("GET","../../process.php?do=add_download_count&file="+fileid);
			xhr.onreadystatechange = callback;
			xhr.send(null);
		}

		// i'm leaving this here for future error handling
		function callback() {
			if (xhr.readyState == 4) {
				if (xhr.status == 200) {
				}
			}
		}
	</script>
	
	<div id="left_column">
	
		<?php if (file_exists('../../img/custom/logo/'.$custom_logo_filename)) { ?>
			<div id="current_logo" class="whitebox">
				<img src="../../includes/thumb.php?src=../img/custom/logo/<?php echo $custom_logo_filename; ?>&amp;w=<?php echo $max_logo_width; ?>&amp;type=tlogo" alt="" />
			</div>
			<div class="clear"></div>
		<?php } ?>

		<div id="help">
			<h2><?php _e('Help','cftp_template'); ?></h2>
			<p><?php _e('The file list on the right contains every file uploaded for you.','cftp_template'); ?></p>
			<p><?php _e('You can click on the name of each marked column to order the list.','cftp_template'); ?></p>
		</div>

	</div>
	
	<div id="right_column">
	
	<?php
		$count=mysql_num_rows($sql);
		if (!$count) {
			_e('There are no files.','cftp_template');
		}
		else {
	?>
	
			<table width="100%" border="0" cellspacing="0" cellpadding="0" id="files_list" class="tablesorter">
			<thead>
				<tr>
					<th><?php _e('Name','cftp_template'); ?></th>
					<th><?php _e('Description','cftp_template'); ?></th>
					<th><?php _e('Size','cftp_template'); ?></th>
					<th><?php _e('Uploaded','cftp_template'); ?></th>
					<th><?php _e('Download','cftp_template'); ?></th>
					<th><?php _e('Image preview','cftp_template'); ?></th>
					<?php // show UPLOADER only to users, not clients
						$clients_allowed = array(9,8,7);
						if (in_session_or_cookies($clients_allowed)) {
					?>
						<th><?php _e('Uploader','cftp_template'); ?></th>
						<th><?php _e('Downloads','cftp_template'); ?></th>
					<?php } ?>
					<th><?php _e('Actions','cftp_template'); ?></th>
				</tr>
			</thead>
			<tbody>
			
			<?php
				while($row = mysql_fetch_array($sql)) {
			?>
			
				<tr>
					<td><?php echo htmlentities($row['filename']); ?></td>
					<td><?php echo htmlentities($row['description']); ?></td>
					<td><?php $entotal = $row['url']; $total = filesize($entotal); getfilesize($total); ?></td>
					<td>
						<?php
						$time_stamp=$row['timestamp']; //get timestamp
						$date_format=date($timeformat,$time_stamp); // formats timestamp
						echo $date_format; // results here ... 02 : 11 : 07
						?>
					</td>
					<td>
						<a href="../../process.php?do=download&amp;client=<?php echo $this_user; ?>&amp;file=<?php echo $row['url']; ?>" target="_blank" class="download" onclick="addDownloadCount(<?php echo $row['id']; ?>);">
							<?php _e('Download','cftp_template'); ?>
						</a>
					</td>
					<td>
						<?php
							$extension = strtolower(substr($row['url'], -3));
							if (
								$extension == "gif" ||
								$extension == "jpg" ||
								$extension == "pjpeg" ||
								$extension == "jpeg" ||
								$extension == "png"
							) {
						?>
							<img src="../../includes/thumb.php?src=../upload/<?php echo $this_user; ?>/<?php echo $row['url']; ?>&amp;w=<?php echo $max_thumbnail_width; ?>&amp;type=prev&amp;who=<?php echo $this_user; ?>&amp;name=<?php echo $row['url']; ?>" class="thumbnail" alt="" />
						<?php } ?>
					</td>
					<?php
						// show UPLOADER only to users, not clients
						$clients_allowed = array(9,8,7);
						if (in_session_or_cookies($clients_allowed)) {
					?>
						<td><?php echo $row['uploader']; ?></td>
						<td><?php echo $row['download_count']; ?></td>
					<?php } ?>
					<td>
						<?php
							// show DELETE FILE only to users, not clients
							$clients_allowed = array(9,8,7);
							if (in_session_or_cookies($clients_allowed)) {
						?>
							<a onclick="return confirm_file_delete();" href="../../process.php?do=del_file&amp;client=<?php echo $this_user; ?>&amp;id=<?php echo $row['id']; ?>&amp;file=<?php echo $row['url']; ?>" target="_self">
								<img src="../../img/icons/delete.png" alt="<?php _e('Delete','cftp_template'); ?>" />
							</a>
						<?php } ?>
					</td>
				</tr>
			
			<?php
				}
			}
			?>
	
				</tbody>
			</table>

<?php if ($count > 10) { ?>
	<div id="pager" class="pager">
		<form>
			<input type="button" class="first pag_btn" value="<?php _e('First','cftp_template'); ?>" />
			<input type="button" class="prev pag_btn" value="<?php _e('Prev.','cftp_template'); ?>" />
			<span><strong><?php _e('Page','cftp_template'); ?></strong>:</span>
			<input type="text" class="pagedisplay" disabled="disabled" />
			<input type="button" class="next pag_btn" value="<?php _e('Next','cftp_template'); ?>" />
			<input type="button" class="last pag_btn" value="<?php _e('Last','cftp_template'); ?>" />
			<span><strong><?php _e('Show','cftp_template'); ?></strong>:</span>
			<select class="pagesize">
				<option selected="selected" value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option value="40">40</option>
			</select>
		</form>
	</div>
<?php } else {?>
	<div id="pager">
		<form>
			<input type="hidden" value="<?php echo $count; ?>" class="pagesize" />
		</form>
	</div>
<?php } ?>
	</div>

</div> <!-- wrapper -->

	<div id="footer">
		<span><?php _e('cFTP Free software (GPL2) | 2007 - ', 'cftp_template'); ?> <?php echo date("Y") ?> | <a href="<?php echo $GLOBALS['uri'];?>" target="_blank"><?php echo $GLOBALS['uri_txt'];?></a></span>
	</div>

</body>
</html>
<?php $database->Close(); ?>