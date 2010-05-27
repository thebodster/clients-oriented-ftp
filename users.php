<?php
	$tablesorter = 1;
	require_once('includes/vars.php');
	$allowed_levels = array(9);
	$page_title = $page_title_users;
	include('header.php');
?>

<script type="text/javascript">
$(document).ready(function()
	{
		$("#users_tbl").tablesorter( {
			sortList: [[0,0]], widgets: ['zebra'], headers: {
				6: { sorter: false }, 
			}
		})
		.tablesorterPager({container: $("#pager")})
	}
);
</script>

<div id="main">
	<h2><?php echo $page_title; ?></h2>
	
	<script type="text/javascript">
		function confirm_delete() {
			if (confirm("<?php echo $userconfdel; ?>")) return true ;
			else return false ;
		}
	</script>

<?php
	$database->MySQLDB();
	$sql = $database->query("SELECT * FROM tbl_users");
	$count=mysql_num_rows($sql);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" id="users_tbl" class="tablesorter">
<thead>
	<tr>
		<th><?php echo $view_user_id; ?></th>
		<th><?php echo $view_user_name; ?></th>
		<th><?php echo $view_user_user; ?></th>
		<th><?php echo $view_user_email; ?></th>
		<th><?php echo $view_user_level; ?></th>
		<th><?php echo $view_user_timestamp; ?></th>
		<th><?php echo $view_actions; ?></th>
	</tr>
</thead>
<tbody>

<?php
	while($row = mysql_fetch_array($sql)) {
	?>
	<tr>
		<td><?php echo $row["id"]?></td>
		<td><?php echo $row["name"]?></td>
		<td><?php echo $row["user"]?></td>
		<td><?php echo $row["email"]?></td>
		<td><?php
			switch($row["level"]) {
				case '9': echo $user_role_lvl9; break;
				case '8': echo $user_role_lvl8; break;
				case '7': echo $user_role_lvl7; break;
			}
		?>
		</td>
		<td>
			<?php
			$time_stamp=$row['timestamp']; //get timestamp
			$date_format=date($timeformat,$time_stamp); // formats timestamp in mm:dd:yy
			echo $date_format; // results here ... 02 : 11 : 07
			?>
		</td>
		<td><?php if ($row["user"] != 'admin') { ?>
			<a onclick="return confirm_delete();" href="process.php?do=del_user&amp;user=<?php echo $row["user"]; ?>" target="_self">
				<img src="img/delete.jpg" alt="<?php echo $userdel; ?>">
			</a>
			<?php } ?>
		</td>
	</tr>
			
	<?php
	}

	$database->Close();
?>

</tbody>
</table>

<?php if ($count > 10) { ?>
<div id="pager" class="pager">
	<form>
		<input type="button" class="first pag_btn" value="<?php echo $pager_first; ?>" />
		<input type="button" class="prev pag_btn" value="<?php echo $pager_prev; ?>" />
		<span><strong>Page</strong>:</span>
		<input type="text" class="pagedisplay" disabled="disabled" />
		<input type="button" class="next pag_btn" value="<?php echo $pager_next; ?>" />
		<input type="button" class="last pag_btn" value="<?php echo $pager_last; ?>" />
		<span><strong>Show</strong>:</span>
		<select class="pagesize">
			<option selected="selected" value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option value="40">40</option>
		</select>
	</form>
</div>
<?php } ?>

</div>

<?php include('footer.php'); ?>