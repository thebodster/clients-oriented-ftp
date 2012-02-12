<?php
$allowed_levels = array(9,8,7);
require_once('includes/includes.php');
$page_title = __('Welcome to cFTP', 'cftp_admin');
include('header.php');
$database->MySQLDB();
?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>

	<div id="intstatbar" class="whitebox">
	
		<!-- Clientes -->
			<div class="statbarlogo" id="stat_clients">
				<span><?php _e('Clients', 'cftp_admin'); ?>:</span>
				<?php
					$sql = $database->query("SELECT distinct client_user FROM tbl_clients");
					$count=mysql_num_rows($sql);
					echo $count;
				?>
				<?php // show VIEW CLIENTS to allowed users
					$clients_allowed = array(9,8);
					if (in_session_or_cookies($clients_allowed)) {
				?>
					<a href="clients.php" target="_self"><?php _e('View', 'cftp_admin'); ?></a>
				<?php } ?>
			</div>

		<?php
			// users stats and logo are only visible by level 9 users (system administrators)
			$allowed = array(9);
			if (in_session_or_cookies($allowed)) {
		?>
		<!-- Usuarios -->								
			<div class="statbarlogo" id="stat_users">
				<span><?php _e('Users', 'cftp_admin'); ?>:</span> 
				<?php
				
					$sql = $database->query("SELECT distinct user FROM tbl_users");
					$count=mysql_num_rows($sql);
					echo $count;
				?>
				<a href="users.php" target="_self"><?php _e('View', 'cftp_admin'); ?></a>
			</div>

		<!-- Logo -->				
			<div class="statbarlogo" id="stat_logo">
				<span><?php _e('Personal logo', 'cftp_admin'); ?>:</span>
				<?php
					if (file_exists('img/custom/logo.jpg')) { _e('Yes', 'cftp_admin'); }
					else { _e('No', 'cftp_admin'); }
				?>
				<a href="logo.php" target="_self"><?php _e('Change', 'cftp_admin'); ?></a>
			</div>
		<?php } ?>

	</div>

	<div id="txthome">
		<p><?php _e('Thank you for choosing cFTP. This software allows you to upload files for specific clients, and keep them stored for as long as you need them.', 'cftp_admin'); ?></p>
		<p><?php _e('cFTP lets choose a name and description for each individual file you upload, and relate it to an existing client or create a new one. When the upload is complete, the system wil give you a link that you can share, where you client can see and download every file available under his account.', 'cftp_admin'); ?></p>
		<p><?php _e("Additionaly, you can select your own logo, that will appear in every client's page.", 'cftp_admin'); ?></p>
	</div>
	
</div>
<?php
$database->Close();
include('footer.php');
?>