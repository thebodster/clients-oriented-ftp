<?php
$page_title = 'Welcome to cFTP';
include('header.php');
?>

<?php $database->MySQLDB(); ?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>

	<div id="intstatbar" class="whitebox">
	
		<!-- Clientes -->
			<div class="statbarlogo" id="stat_clients">
				<span><?php echo $statcli; ?>:</span>
				<?php

					$sql = $database->query("SELECT distinct client_user FROM tbl_clients");
					$count=mysql_num_rows($sql);
					echo $count;
				?>
				<a href="clients.php" target="_self"><?php echo $statview; ?></a>
			</div>

		<!-- Usuarios -->								
			<div class="statbarlogo" id="stat_users">
				<span><?php echo $statusr; ?>:</span> 
				<?php
				
					$sql = $database->query("SELECT distinct user FROM tbl_users");
					$count=mysql_num_rows($sql);
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

<?php $database->Close(); ?>