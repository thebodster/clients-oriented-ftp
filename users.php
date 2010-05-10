<?php include('header.php'); ?>

<script type="text/javascript">
$(document).ready(function()
	{
		$("#users_tbl").tablesorter( {
			sortList: [[0,0]], widgets: ['zebra'], headers: {
				6: { sorter: false }, 
			}
		});
	}
);
</script>

<div id="main">
	<h2><?php echo $ti_usrs; ?></h2>
	
<script type="text/javascript">
	function confdel() {
		if (confirm("<?php echo $userconfdel; ?>")) return true ;
		else return false ;
	}
</script>

<?php

	$sqllink = mysql_connect($host, $dbuser, $dbpass)or die('Cant connect to database');
	mysql_select_db($dbname)or die('Database not found');

	$sql="SELECT * FROM tbl_users";
	$result=mysql_query($sql);
	
	$count=mysql_num_rows($result);
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
	while($row = mysql_fetch_array($result)) {
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
			<a onclick="return confdel();" href="<?php echo $baseuri;?>deleteuser.php?user=<?php echo $row["user"]; ?>" target="_self">
				<img src="img/delete.jpg" alt="<?php echo $userdel; ?>">
			</a>
			<?php } ?>
		</td>
	</tr>
			
	<?php
	}

	mysql_close($sqllink);
?>

</tbody>
</table>

</div>

<?php include('footer.php'); ?>