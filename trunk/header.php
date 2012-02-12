<?php
session_start();
ob_start();
header("Cache-control: private");
check_for_session();
check_for_admin();
if (!isset($page_title)) { $page_title = $page_title_basic; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this_install_title; ?> &raquo; <?php echo $page_title; ?> | <?php echo $short_system_name; ?></title>
<link rel="shortcut icon" href="favicon.ico" />
<link rel="stylesheet" media="all" type="text/css" href="styles/base.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script src="includes/js/dropdownmenu.js" type="text/javascript"></script>
<?php if (isset($tablesorter)) { ?>
<script src="includes/js/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="includes/js/jquery.tablesorter.pager.js" type="text/javascript"></script>
<?php } ?>
<?php if (isset($textboxlist)) { ?>
<script src="includes/js/mootools-1.2.1-core-yc.js" type="text/javascript"></script>
<script src="includes/js/GrowingInput.js" type="text/javascript"></script>
<script src="includes/js/TextboxList.js" type="text/javascript"></script>
<?php } ?>
</head>

<body>
<div id="wrapper">
	<div id="header">
		<p id="cftptop"><?php echo $full_system_name; ?></p>
		<p><?php echo $version; ?> <?php echo $curver; ?></p>
		<a href="process.php?do=logout" target="_self"><img src="img/logout.gif" alt="<?php _e('Logout', 'cftp_admin'); ?>" id="logout" /></a>
	</div>

<div id="top_menu">
	<ul class="menu" id="menu">
		<li><a href="home.php" class="menulink"><?php _e('Home', 'cftp_admin'); ?></a></li>
		<li><a href="fileupload.php" class="menulink"><?php _e('Upload files', 'cftp_admin'); ?></a></li>

		<?php // show CLIENTS to allowd users
			$clients_allowed = array(9,8);
			if (in_session_or_cookies($clients_allowed)) {
		?>
		<li>
			<a href="#" class="menulink dropready"><?php _e('Clients', 'cftp_admin'); ?></a>
			<ul>
				<li><a href="clientform.php"><?php _e('Add new', 'cftp_admin'); ?></a></li>
				<li><a href="clients.php"><?php _e('Manage clients', 'cftp_admin'); ?></a></li>
			</ul>
		</li>
		<?php } ?>

		<?php // show USERS to allowd users
			$users_allowed = array(9);
			if (in_session_or_cookies($users_allowed)) {
		?>
		<li>
			<a href="#" class="menulink dropready"><?php _e('Users', 'cftp_admin'); ?></a>
			<ul>
				<li><a href="userform.php"><?php _e('Add new', 'cftp_admin'); ?></a></li>
				<li><a href="users.php"><?php _e('Manage users', 'cftp_admin'); ?></a></li>
			</ul>
		</li>
		<?php } ?>

		<?php // show LOGO and OPTIONS to allowd users
			$options_allowed = array(9);
			if (in_session_or_cookies($options_allowed)) {
		?>
		<li>
			<a href="#" class="menulink dropready"><?php _e('Options', 'cftp_admin'); ?></a>
			<ul>
				<li><a href="options.php"><?php _e('General options', 'cftp_admin'); ?></a></li>
				<li><a href="logo.php"><?php _e('Your logo', 'cftp_admin'); ?></a></li>
			</ul>
		</li>
		<?php } ?>

	</ul>
	<div class="clear"></div>
</div>
<?php can_see_content($allowed_levels,$page_title_not_allowed,$userlevel_not_allowed); ?>