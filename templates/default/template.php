<?php
/*
Template name:
Default
*/

$ld = 'cftp_template'; // specify the language domain for this template
include_once('../../templates/common.php'); // include the required functions for every template

$window_title = __('File downloads','cftp_template');

// User or client?
$actions_allowed = array(9,8,7);
if (in_session_or_cookies($actions_allowed)) {
	$view_actions = 1;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $user_full_name.' | '.$window_title; ?> | <?php echo SYSTEM_NAME; ?></title>
<link rel="shortcut icon" href="../../favicon.ico" />
<link rel="stylesheet" media="all" type="text/css" href="../../styles/shared.css" />
<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this_template; ?>main.css" />
<link rel="stylesheet" media="all" type="text/css" href="../../styles/font-sansation.css" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script src="../../includes/js/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="../../includes/js/jquery.tablesorter.pager.js" type="text/javascript"></script>
</head>

<body>

<div id="header">
	<div id="header_info">
		<h1><?php echo SYSTEM_NAME; ?></h1>
	</div>
	<a href="../../process.php?do=logout" target="_self" id="logout"><?php _e('Logout', 'cftp_admin'); ?></a>
</div>

<div id="under_header">
	<p><?php echo $user_full_name, ', '; _e('welcome to your downloads', 'cftp_admin'); ?></p>
</div>

<div id="wrapper">


	<script type="text/javascript">
		$(document).ready(function()
			{
				$("#files_list")
					.tablesorter( {
						sortList: [[0,1]], widgets: ['zebra'], headers: {
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
	</script>

	<div id="left_column">
		<div id="current_logo">
			<img src="../../includes/thumb.php?src=../img/custom/logo/<?php echo LOGO_FILENAME; ?>&amp;w=250&amp;type=tlogo" alt="" />
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

			<table id="files_list" class="tablesorter">
			<thead>
				<tr>
					<th><?php _e('Uploaded','cftp_template'); ?></th>
					<th><?php _e('Name','cftp_template'); ?></th>
					<th><?php _e('Description','cftp_template'); ?></th>
					<th><?php _e('Size','cftp_template'); ?></th>
					<th><?php _e('Image preview','cftp_template'); ?></th>
					<?php // show UPLOADER only to users, not clients
						$clients_allowed = array(9,8,7);
						if (in_session_or_cookies($clients_allowed)) {
					?>
						<th><?php _e('Uploader','cftp_template'); ?></th>
						<th><?php _e('Downloads','cftp_template'); ?></th>
					<?php } ?>
					<th><?php _e('Download','cftp_template'); ?></th>
					<?php
						if(isset($view_actions) && $view_actions === 1) {
					?>
							<th><?php _e('Actions','cftp_template'); ?></th>
					<?php
						}
					?>
				</tr>
			</thead>
			<tbody>
			
			<?php
				while($row = mysql_fetch_array($sql)) {
			?>
			
				<tr>
					<td>
						<?php
						$time_stamp=$row['timestamp']; //get timestamp
						$date_format=date(TIMEFORMAT_USE,$time_stamp); // formats timestamp
						echo $date_format; // results here ... 02 : 11 : 07
						?>
					</td>
					<td><strong><?php echo htmlentities($row['filename']); ?></strong></td>
					<td><?php echo htmlentities($row['description']); ?></td>
					<td><?php $this_file = filesize($row['url']); echo format_file_size($this_file); ?></td>
					<td>
						<?php
							$pathinfo = pathinfo($row['url']);
							$extension = $pathinfo['extension'];
							if (
								$extension == "gif" ||
								$extension == "jpg" ||
								$extension == "pjpeg" ||
								$extension == "jpeg" ||
								$extension == "png"
							) {
						?>
							<img src="../../includes/thumb.php?src=../upload/<?php echo $this_user; ?>/<?php echo $row['url']; ?>&amp;w=<?php echo THUMBS_MAX_WIDTH; ?>&amp;type=prev&amp;who=<?php echo $this_user; ?>&amp;name=<?php echo $row['url']; ?>" class="thumbnail" alt="" />
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
						<a href="../../process.php?do=download&amp;client=<?php echo $this_user; ?>&amp;file=<?php echo $row['url']; ?>" target="_blank" class="button button_blue">
							<?php _e('Download','cftp_template'); ?>
						</a>
					</td>
					<?php
					// Actions column
					if(isset($view_actions) && $view_actions === 1) {
					?>
						<td>
							<?php
								if($row['hidden'] === '0' || empty($row['hidden'])) {
							?>
									<a href="../../process.php?do=hide_file&amp;client=<?php echo $this_user; ?>&amp;id=<?php echo $row['id']; ?>" class="button button_small button_red"><?php _e('Hide','cftp_admin'); ?></a>
							<?php
								} else {
							?>
									<a href="../../process.php?do=show_file&amp;client=<?php echo $this_user; ?>&amp;id=<?php echo $row['id']; ?>" class="button button_small button_green"><?php _e('Show','cftp_admin'); ?></a>
							<?php
								}
								// show DELETE FILE only to users (except uploaders), not clients
								$delete_allowed = array(9,8);
								if (in_session_or_cookies($delete_allowed)) {
							?>
								<a href="../../process.php?do=del_file&amp;client=<?php echo $this_user; ?>&amp;id=<?php echo $row['id']; ?>&amp;file=<?php echo $row['url']; ?>" class="button button_small button_red" onclick="return confirm_file_delete();"><?php _e('Delete','cftp_admin'); ?></a>
							<?php } ?>
						</td>
					<?php } ?>
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

	</div> <!-- right_column -->


</div> <!-- wrapper -->

<?php default_footer_info(); ?>

</body>
</html>
<?php $database->Close(); ?>