<?php include('header.php'); ?>

<?php 				
	$sqllink = mysql_connect($host, $dbuser, $dbpass)or die('Cant connect to database');
	mysql_select_db($dbname)or die('Database not found');
?>

<div id="main">
	<h2><?php echo $tihome; ?></h2>

	<div id="intstatbar" class="whitebox">
	
		<!-- Clientes -->
			<div class="statbarlogo" id="stat_clients">
				<span><?php echo $statcli; ?>:</span>
				<?php

					$sql="SELECT distinct client_user FROM tbl_clients";
					$result=mysql_query($sql);
					
					$count=mysql_num_rows($result);
					
					echo $count;
				?>
				<a href="clients.php" target="_self"><?php echo $statview; ?></a>
			</div>

		<!-- Usuarios -->								
			<div class="statbarlogo" id="stat_users">
				<span><?php echo $statusr; ?>:</span> 
				<?php
				
					$sql="SELECT distinct user FROM tbl_users";
					$result=mysql_query($sql);
					
					$count=mysql_num_rows($result);
					
					echo $count;
				?>
				<a href="users.php" target="_self"><?php echo $statview; ?></a>
			</div>
		<!-- Logo -->				
			<div class="statbarlogo" id="stat_logo">
				<span><?php echo $statlogo; ?>:</span>
				<?php
					if (file_exists('img/custom/logo.jpg')) { echo $yes; }
					else { echo $no; }
				?>
				<a href="logo.php" target="_self"><?php echo $stat_logo_change; ?></a>
			</div>	
	</div>

	<div id="txthome"><?php echo $txthome; ?></div>
	
</div>

<?php include('footer.php'); ?>