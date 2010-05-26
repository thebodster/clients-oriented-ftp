<?php
	$tablesorter = 1;
	require_once('includes/vars.php');
	$page_title = $page_title_clients;
	include('header.php');
?>

<script type="text/javascript">
$(document).ready(function()
	{
		$("#clients_tbl").tablesorter( {
			sortList: [[0,0]], widgets: ['zebra'], headers: {
				9: { sorter: false }, 
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
			if (confirm("<?php echo $confdel; ?>")) return true ;
			else return false ;
		}
	</script>

<?php
	$database->MySQLDB();
	$sql = $database->query("SELECT * FROM tbl_clients");
	$count=mysql_num_rows($sql);
	if (!$count) {
		echo $noclients;
	}
	else {
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" id="clients_tbl" class="tablesorter">
<thead>
	<tr>
		<th><?php echo $view_cid; ?></th>
		<th><?php echo $view_cname; ?></th>
		<th><?php echo $view_cuser; ?></th>
		<th><?php echo $view_cadd; ?></th>
		<th><?php echo $view_cphone; ?></th>
		<th><?php echo $view_cmail; ?></th>
		<th><?php echo $view_cnoti; ?></th>
		<th><?php echo $view_ccont; ?></th>
		<th><?php echo $view_client_timestamp; ?></th>
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
		<td><?php echo $row["client_user"]?></td>
		<td><?php echo $row["address"]?></td>
		<td><?php echo $row["phone"]?></td>
		<td><?php echo $row["email"]?></td>
		<td><?php if ($row["notify"] == '1') { echo $yes; } else { echo $no; }?></td>
		<td><?php echo $row["contact"]?></td>
		<td>
			<?php
			$time_stamp=$row['timestamp']; //get timestamp
			$date_format=date($timeformat,$time_stamp); // formats timestamp in mm:dd:yy
			echo $date_format; // results here ... 02 : 11 : 07
			?>
		</td>
		<td>
			<a onclick="return confirm_delete();" href="process.php?do=del_client&amp;client=<?php echo $row["client_user"]; ?>" target="_self"><img src="img/delete.jpg" alt="<?php echo $cldel; ?>"></a>
			<a href="<?php echo $baseuri;?>upload/<?php echo $row["client_user"]; ?>/" target="_blank"><img src="img/view.jpg" alt="<?php echo $clview; ?>"></a>
		</td>
	</tr>

	<?php
		}
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

</div>

<?php include('footer.php'); ?>