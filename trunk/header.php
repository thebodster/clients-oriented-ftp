<?php
/**
 * This file generates the header back-end part only if the current logged in
 * account is that of a system user. Clients are redirected to thir files lists
 * via index.php.
 *
 * Other checks for user level are performed later to generate the different
 * menu items, and the content of the page that called this file.
 *
 * @package ProjectSend
 * @see check_for_session
 * @see check_for_admin
 * @see can_see_content
 */
session_start();
ob_start();
header("Cache-control: private");

/** Check for an active session or cookie */
check_for_session();

/** Check if the active account belongs to a system user or a client. */
check_for_admin();

/** If no page title is defined, revert to a default one */
if (!isset($page_title)) { $page_title = __('System Administration','cftp_admin'); }

/** Call the database update file to see if any change is needed */
require_once('includes/core.update.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo THIS_INSTALL_SET_TITLE; ?> &raquo; <?php echo $page_title; ?> | <?php echo SYSTEM_NAME; ?></title>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" media="all" type="text/css" href="styles/shared.css" />
	<link rel="stylesheet" media="all" type="text/css" href="styles/base.css" />
	<link rel="stylesheet" media="all" type="text/css" href="styles/font-sansation.css" />
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script src="includes/js/dropdownmenu.js" type="text/javascript"></script>
	<script type="text/javascript" src="includes/js/jquery.validations.js"></script>

	<?php if (isset($tablesorter)) { ?>
		<script src="includes/js/jquery.tablesorter.min.js" type="text/javascript"></script>
		<script src="includes/js/jquery.tablesorter.pager.js" type="text/javascript"></script>
	<?php } ?>

	<?php if (isset($textboxlist)) { ?>
		<script src="includes/js/GrowingInput.js" type="text/javascript"></script>
		<script src="includes/js/TextboxList.js" type="text/javascript"></script>
	<?php } ?>

	<?php if (isset($plupload)) { ?>
		<link rel="stylesheet" media="all" type="text/css" href="includes/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css" />
		<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
		<script type="text/javascript" src="includes/plupload/js/plupload.full.js"></script>
		<script type="text/javascript" src="includes/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js"></script>
	<?php } ?>

</head>

<body>

	<div id="header">
		<div id="header_info">
			<h1><?php echo SYSTEM_NAME; ?></h1>
			<p><?php echo CURRENT_VERSION; ?></p>
		</div>
		<a href="process.php?do=logout" target="_self" id="logout"><?php _e('Logout', 'cftp_admin'); ?></a>
	</div>

	<?php
		/**
		 * If any update was made to the database structure, show the message
		 */
		if($updates_made > 0) {
	?>
			<div id="system_msg">
				<p><strong><?php _e('System Notice:', 'cftp_admin');?></strong> <?php _e('The database was updated to support this version of the software: ', 'cftp_admin'); echo CURRENT_VERSION; ?></p>
			</div>
	<?php
		}
	?>
	
	<div id="top_menu">
		<ul class="menu" id="menu">
			<li><a href="home.php" class="menulink"><?php _e('Home', 'cftp_admin'); ?></a></li>
			<li>
				<a href="upload-from-computer.php" class="menulink dropready"><?php _e('Upload files', 'cftp_admin'); ?></a>
				<ul>
					<li><a href="upload-from-computer.php"><?php _e('Upload from computer', 'cftp_admin'); ?></a></li>
					<li><a href="upload-by-ftp.php"><?php _e('Import from FTP', 'cftp_admin'); ?></a></li>
				</ul>
			</li>
	
			<?php
				/**
				 * Show the CLIENTS menu only to
				 * System administrators and Account managers
				 */
				$clients_allowed = array(9,8);
				if (in_session_or_cookies($clients_allowed)) {
			?>
					<li>
						<a href="clients.php" class="menulink dropready"><?php _e('Clients', 'cftp_admin'); ?></a>
						<ul>
							<li><a href="clientform.php"><?php _e('Add new', 'cftp_admin'); ?></a></li>
							<li><a href="clients.php"><?php _e('Manage clients', 'cftp_admin'); ?></a></li>
						</ul>
					</li>
			<?php } ?>
	
			<?php
				/**
				 * Show the USERS menu only to
				 * System administrators
				 */
				$users_allowed = array(9);
				if (in_session_or_cookies($users_allowed)) {
			?>
					<li>
						<a href="users.php" class="menulink dropready"><?php _e('Users', 'cftp_admin'); ?></a>
						<ul>
							<li><a href="userform.php"><?php _e('Add new', 'cftp_admin'); ?></a></li>
							<li><a href="users.php"><?php _e('Manage users', 'cftp_admin'); ?></a></li>
						</ul>
					</li>
			<?php } ?>
	
			<?php
				/**
				 * Show the OPTIONS menu only to
				 * System administrators
				 */
				$options_allowed = array(9);
				if (in_session_or_cookies($options_allowed)) {
			?>
					<li>
						<a href="options.php" class="menulink dropready"><?php _e('Options', 'cftp_admin'); ?></a>
						<ul>
							<li><a href="options.php"><?php _e('General options', 'cftp_admin'); ?></a></li>
							<li><a href="branding.php"><?php _e('Branding', 'cftp_admin'); ?></a></li>
						</ul>
					</li>
			<?php } ?>
	
		</ul>
		<div class="clear"></div>
	</div>

<?php
	/**
	 * Check if the current user has permission to view this page.
	 * If not, an error message is generated instead of the actual content.
	 * The allowed levels are defined on each individual page before the
	 * inclusion of this file.
	 */
	can_see_content($allowed_levels);
?>