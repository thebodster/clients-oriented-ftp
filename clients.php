<?php
	$tablesorter = 1;
	$allowed_levels = array(9,8);
	require_once('includes/includes.php');
	$page_title = __('Clients Administration','cftp_admin');
	include('header.php');
?>

<script type="text/javascript">
$(document).ready(function() {
	$("#clients_tbl").tablesorter( {
		sortList: [[0,0]], widgets: ['zebra'], headers: {
			9: { sorter: false }
		}
	})
	.tablesorterPager({container: $("#pager")})
});
</script>

<div id="main">
	<h2><?php echo $page_title; ?></h2>
	
	<script type="text/javascript">
		function confirm_delete() {
			if (confirm("<?php _e("This will delete the folder and all of the client's files. Continue?",'cftp_admin'); ?>")) return true ;
			else return false ;
		}
	</script>

<?php
	$database->MySQLDB();
	$cq = "SELECT * FROM tbl_clients";

/*
	// if the current user role is "account manager", only show the clients created by this user
	if (get_current_user_level() == '8') {
		$u = get_current_user_username();
		$cq .= " WHERE created_by = '$u'";
	}
*/
	$sql = $database->query($cq);
	$count=mysql_num_rows($sql);
	if (!$count) {
	?>
		<div class="whiteform whitebox">
			<p><?php _e('There are no clients at the moment.','cftp_admin'); ?></p>
			<p><?php _e('Please create at least one to be able to upload files.','cftp_admin'); ?></p>
		</div>
	<?php
	}
	else {
?>

<form action="clients.php" name="clients_list" method="post">
	<table id="clients_tbl" class="tablesorter vertical_middle">
	<thead>
		<tr>
			<th><?php _e('Full name','cftp_admin'); ?></th>
			<th><?php _e('Log in username','cftp_admin'); ?></th>
			<th><?php _e('Address','cftp_admin'); ?></th>
			<th><?php _e('Telephone','cftp_admin'); ?></th>
			<th><?php _e('E-mail','cftp_admin'); ?></th>
			<th><?php _e('Notify','cftp_admin'); ?></th>
			<th><?php _e('Internal contact','cftp_admin'); ?></th>
			<th><?php _e('Added on','cftp_admin'); ?></th>
			<th><?php _e('Files','cftp_admin'); ?></th>
			<th><?php _e('Actions','cftp_admin'); ?></th>
		</tr>
	</thead>
	<tbody>
	
	<?php
			while($row = mysql_fetch_array($sql)) {
			$client_user = $row["client_user"];
	?>
	
		<tr>
			<td><?php echo $row["name"]; ?></td>
			<td><?php echo $row["client_user"]; ?></td>
			<td><?php echo $row["address"]; ?></td>
			<td><?php echo $row["phone"]; ?></td>
			<td><?php echo $row["email"]; ?></td>
			<td><?php if ($row["notify"] == '1') { _e('Yes','cftp_admin'); } else { _e('No','cftp_admin'); }?></td>
			<td><?php echo $row["contact"]; ?></td>
			<td>
				<?php
				$time_stamp=$row['timestamp']; //get timestamp
				$date_format=date(TIMEFORMAT_USE,$time_stamp); // formats timestamp in mm:dd:yy
				echo $date_format; // results here ... 02 : 11 : 07
				?>
			</td>
			<td>
				<?php
					$sql_files = $database->query("SELECT * FROM tbl_files WHERE client_user='$client_user'");
					$count_files=mysql_num_rows($sql_files);
					echo $count_files;
				?>
			</td>
			<td>
				<a href="manage-files.php?id=<?php echo $row["id"]; ?>" class="button button_blue"><?php _e('Manage files','cftp_admin'); ?></a>
				<a href="upload/<?php echo $row["client_user"]; ?>/" class="button button_blue" target="_blank"><?php _e('View as client','cftp_admin'); ?></a>
				<a href="clientform.php?do=edit&amp;client=<?php echo $row["id"]; ?>" class="button button_small button_blue"><?php _e('Edit','cftp_admin'); ?></a>
				<a href="process.php?do=del_client&amp;client=<?php echo $row["client_user"]; ?>" class="button button_small button_red" onclick="return confirm_delete();"><?php _e('Delete','cftp_admin'); ?></a>
			</td>
		</tr>
	
		<?php
			}
		}
	
		$database->Close();
	?>
	
	</tbody>
	</table>
</form>

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

	</div>

</div>

<?php include('footer.php'); ?>