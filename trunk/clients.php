<?php include('header.php'); ?>

<script type="text/javascript">
$(document).ready(function()
	{
		$("#clients_tbl").tablesorter( {
			sortList: [[0,0]], widgets: ['zebra'], headers: {
				9: { sorter: false }, 
			}
		});
	}
);
</script>

<div id="main">
	<h2><?php echo $ticli; ?></h2>
	
	
<script type="text/javascript">
	function confdel() {
		if (confirm("<?php echo $confdel; ?>")) return true ;
		else return false ;
	}
</script>

<?php

	$sqllink = mysql_connect($host, $dbuser, $dbpass)or die('Cant connect to database');
	mysql_select_db($dbname)or die('Database not found');

	$sql="SELECT * FROM tbl_clients";
	$result=mysql_query($sql);
	
	$count=mysql_num_rows($result);
	if (!$count) { echo $noclients; }
	
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
		while($row = mysql_fetch_array($result)) {
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
			<a onclick="return confdel();" href="<?php echo $baseuri;?>deleteclient.php?client=<?php echo $row["client_user"]; ?>" target="_self"><img src="img/delete.jpg" alt="<?php echo $cldel; ?>"></a>
			<a href="<?php echo $baseuri;?>upload/<?php echo $row["client_user"]; ?>/" target="_blank"><img src="img/view.jpg" alt="<?php echo $clview; ?>"></a>
		</td>
	</tr>

	<?php
		}
	}

	mysql_close($sqllink);
?>

</tbody>
</table>

	</div>

</div>

<?php include('footer.php'); ?>