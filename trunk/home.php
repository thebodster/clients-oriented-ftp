<?php
require_once('includes/vars.php');
$page_title = $page_title_home;
include('header.php');
$database->MySQLDB();
?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>

	<div id="intstatbar" class="whitebox">
	
		<!-- Clientes -->
			<div class="statbarlogo" id="stat_clients">
				<span><?php echo $statistics_clients; ?>:</span>
				<?php
					$sql = $database->query("SELECT distinct client_user FROM tbl_clients");
					$count=mysql_num_rows($sql);
					echo $count;
				?>
				<?php // show VIEW CLIENTS to allowed users
					$clients_allowed = array(9,8);
					if (in_array($_SESSION['userlevel'],$clients_allowed)) {
				?>
					<a href="clients.php" target="_self"><?php echo $statistics_view; ?></a>
				<?php } ?>
			</div>

		<?php
			// users stats and logo are only visible by level 9 users (system administrators)
			$allowed = array(9);
			if (in_array($_SESSION['userlevel'],$allowed)) {
		?>
		<!-- Usuarios -->								
			<div class="statbarlogo" id="stat_users">
				<span><?php echo $statistics_users; ?>:</span> 
				<?php
				
					$sql = $database->query("SELECT distinct user FROM tbl_users");
					$count=mysql_num_rows($sql);
					echo $count;
				?>
				<a href="users.php" target="_self"><?php echo $statistics_view; ?></a>
			</div>

		<!-- Logo -->				
			<div class="statbarlogo" id="stat_logo">
				<span><?php echo $statistics_logo; ?>:</span>
				<?php
					if (file_exists('img/custom/logo.jpg')) { echo $yes; }
					else { echo $no; }
				?>
				<a href="logo.php" target="_self"><?php echo $stat_logo_change; ?></a>
			</div>
		<?php } ?>

	</div>

	<div id="txthome"><?php echo $home_intro_text; ?></div>
	
</div>
<?php
$database->Close();
include('footer.php');
?>