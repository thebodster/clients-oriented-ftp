<?php
	$tablesorter = 1;
	$allowed_levels = array(9,8);
	require_once('includes/includes.php');

	$this_id = $_GET['id'];
	$this_client = get_client_information($this_id);

	$page_title = __('Manage files','cftp_admin');
	if(!empty($this_client)) {
		$page_title .= ' for '.$this_client['name'];
	}
	include('header.php');
	
?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#files_list")
			.tablesorter( {
				sortList: [[0,1]], widgets: ['zebra'], headers: {
					7: { sorter: false }
				}
		})
		.tablesorterPager({container: $("#pager")})
	});

	function confirm_file_delete() {
		if (confirm("<?php _e('This will delete the file permanently. Continue?','cftp_template'); ?>")) return true ;
		else return false ;
	}
</script>

<div id="main">
	<h2><?php echo $page_title; ?></h2>

	<?php
		if(empty($this_id)) {
	?>
			<div class="whiteform whitebox">
				<p><?php _e('Please go to the clients administration page and select "Manage files" from any client.','cftp_admin'); ?></p>
			</div>
	<?php
		}
		else {
	?>
	
		<?php
			$database->MySQLDB();
			$files_query = 'SELECT * FROM tbl_files WHERE client_user="' . $this_client['username'] .'"';
		
			$sql = $database->query($files_query);
			$count = mysql_num_rows($sql);
			if (!$count) {
			?>
				<div class="whiteform whitebox">
					<p><?php _e('There are no files for this client.','cftp_admin'); ?></p>
				</div>
			<?php
			}
			else {
		?>
	
				<p><?php _e('Please note that downloading a file from here will not add to the download count.','cftp_admin'); ?></p>
	
				<table id="files_list" class="tablesorter">
					<thead>
						<tr>
							<th><?php _e('Uploaded','cftp_template'); ?></th>
							<th><?php _e('Name','cftp_template'); ?></th>
							<th><?php _e('Description','cftp_template'); ?></th>
							<th><?php _e('Size','cftp_template'); ?></th>
							<th><?php _e('Status','cftp_template'); ?></th>
							<th><?php _e('Uploader','cftp_template'); ?></th>
							<th><?php _e('Download count','cftp_template'); ?></th>
							<th><?php _e('Actions','cftp_template'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							while($row = mysql_fetch_array($sql)) {
								$this_file_uri = 'upload/'.$this_client['username'].'/'.$row['url'];
						?>
								<tr>
									<td><?php echo date(TIMEFORMAT_USE, $row['timestamp']); ?></td>
									<td><strong><?php echo htmlentities($row['filename']); ?></strong></td>
									<td><?php echo htmlentities($row['description']); ?></td>
									<td><?php $this_file = filesize($this_file_uri); echo format_file_size($this_file); ?></td>
									<td>
										<?php
											$status_hidden = __('Hidden','cftp_admin');
											$status_visible = __('Visible','cftp_admin');
											echo ($row['hidden'] === '1') ? $status_hidden : $status_visible;
										?>
									</td>
									<td><?php echo $row['uploader']; ?></td>
									<td><?php echo $row['download_count']; ?></td>
									<td>
										<a href="<?php echo $this_file_uri; ?>" target="_blank" class="button button_blue">
											<?php _e('Download','cftp_template'); ?>
										</a>
										<?php
											if($row['hidden'] === '0' || empty($row['hidden'])) {
										?>
												<a href="process.php?do=hide_file&amp;client=<?php echo $this_id; ?>&amp;id=<?php echo $row['id']; ?>" class="button button_small button_red"><?php _e('Hide','cftp_admin'); ?></a>
										<?php
											} else {
										?>
												<a href="process.php?do=show_file&amp;client=<?php echo $this_id; ?>&amp;id=<?php echo $row['id']; ?>" class="button button_small button_green"><?php _e('Show','cftp_admin'); ?></a>
										<?php
											}
										?>
										<a href="process.php?do=del_file&amp;client_id=<?php echo $this_id; ?>&amp;client_user=<?php echo $this_client['username']; ?>&amp;file_id=<?php echo $row['id']; ?>&amp;file_name=<?php echo $row['url']; ?>" class="button button_small button_red" onclick="return confirm_file_delete();"><?php _e('Delete','cftp_admin'); ?></a>
									</td>
								</tr>
						<?php
							}
						?>
					</tbody>
				</table>
	
				<?php if ($count > 10) { ?>
					<div id="pager" class="pager">
						<form>
							<input type="button" class="first pag_btn" value="<?php _e('First','cftp_admin'); ?>" />
							<input type="button" class="prev pag_btn" value="<?php _e('Prev.','cftp_admin'); ?>" />
							<span><strong><?php _e('Page','cftp_admin'); ?></strong>:</span>
							<input type="text" class="pagedisplay" disabled="disabled" />
							<input type="button" class="next pag_btn" value="<?php _e('Next','cftp_admin'); ?>" />
							<input type="button" class="last pag_btn" value="<?php _e('Last','cftp_admin'); ?>" />
							<span><strong><?php _e('Show','cftp_admin'); ?></strong>:</span>
							<select class="pagesize">
								<option selected="selected" value="10">10</option>
								<option value="20">20</option>
								<option value="30">30</option>
								<option value="40">40</option>
							</select>
						</form>
					</div>
				<?php } else { ?>
					<div id="pager">
						<form>
							<input type="hidden" value="<?php echo $count; ?>" class="pagesize" />
						</form>
					</div>
				<?php } ?>
	
	<?php
		// End IF COUNT
		}
	
		$database->Close();
	}
	?>

	</div>

</div>

<?php include('footer.php'); ?>