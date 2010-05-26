<?php
session_start();
ob_start();
/*
	cFTP on Google Code
	http://code.google.com/p/clients-oriented-ftp/
	Distributed under GPL2
	Feel free to participate!
*/
require_once('includes/vars.php');
require_once('includes/sys.vars.php');
require_once('includes/site.options.php');
require_once('includes/functions.php');
//if logged as a system user, go directly to home.php
if ($_SESSION['access'] == 'admin') {
header("location:home.php");
}
$database->MySQLDB();
$sysuser_username=mysql_real_escape_string($_POST['login_form_user']);
$sysuser_password=mysql_real_escape_string(md5($_POST['login_form_pass']));
$client_username=mysql_real_escape_string($_POST['login_form_client_user']);
$client_password=mysql_real_escape_string(md5($_POST['login_form_client_pass']));
// thanks to http://www.kminek.pl/lab/yetii/ for the tabs script!
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $page_title_login; ?> | <?php echo $short_system_name; ?></title>
<link rel="shortcut icon" href="favicon.ico" />
<link rel="stylesheet" media="all" type="text/css" href="styles/base.css" />
<script type="text/javascript" src="includes/js/yetii-min.js"></script>
</head>

<body>
<div id="main">

<?php
if ($_POST) {

	if (isset($_POST['sent_admin'])) {
		// try to login as system user
		$sql = $database->query("SELECT * FROM tbl_users WHERE user='$sysuser_username' and password='$sysuser_password'");
		$count=mysql_num_rows($sql);
		
		if($count>0){
			// changes here should also be reflected on header.php
			$_SESSION['loggedin'] = $sysuser_username;
			$_SESSION['access'] = 'admin';
			header("location:home.php");
		}
		else {
			$errorstate = 'admin_not_exists';
		}
	}
	elseif (isset($_POST['sent_client'])) {
		// try to login as client		
		$sql = $database->query("SELECT * FROM tbl_clients WHERE client_user='$client_username' and password='$client_password'");
		$count=mysql_num_rows($sql);
		
		if($count>0){
			// changes here should also be reflected on templates
			$_SESSION['loggedin'] = $client_username;
			$_SESSION['access'] = $client_username;
			header("location:upload/$client_username/");
		}
		else {
			$errorstate = 'client_not_exists';
		}
	}

}
?>
	
	<div id="lonely_logo">
		<p><?php echo $full_system_name; ?></p>
	</div>
	<div class="whiteform whitebox" id="loginform">
	
		<h3><?php echo $login_title; ?></h3>
		<h4><?php echo $login_tips; ?></h4>

		<div class="login_divide"></div>

		<?php
			// show login errors here. TO DO: show different messages for admin or clients errors
			if (isset($errostate) || $errorstate == 'admin_not_exists' || $errorstate == 'client_not_exists') { ?>
			<div class="message message_error" id="login_error">
				<p><?php echo $login_err.' | '.$login_err2; ?></p>
			</div>
		<?php } ?>

		<?php include_once('includes/js/js.validations.php'); ?>
	
		<script type="text/javascript">
		
			window.onload = default_field;
	
			var js_err_user = "<?php echo $validation_no_user; ?>"
			var js_err_pass = "<?php echo $validation_no_pass; ?>"
	
			function validateadmin(theform){
				is_complete(theform.login_form_user,js_err_user);
				is_complete(theform.login_form_pass,js_err_pass);
				// show the errors or continue if everything is ok
				if (error_list != '') {
					alert(error_title+error_list)
					error_list = '';
					return false;
				}
			}

			function validateclient(theform){
				is_complete(theform.login_form_client_user,js_err_user);
				is_complete(theform.login_form_client_pass,js_err_pass);
				// show the errors or continue if everything is ok
				if (error_list != '') {
					alert(error_title+error_list)
					error_list = '';
					return false;
				}
			}
	
		</script>
	
		<div id="login-tabs" class="tabs_layout">

			<ul id="login-tabs-nav" class="tabs_layout">
				<li><a href="#tab_sysuser" id="tab_users"><?php echo $login_tab_admin; ?></a></li>
				<li><a href="#tab_client" id="tab_clients"><?php echo $login_tab_client; ?></a></li>
			</ul>

			<div class="tabs-container">

				<div class="tab" id="tab_sysuser">
					<form action="index.php" method="post" name="login_admin" onsubmit="return validateadmin(this);">
						<input type="hidden" name="sent_admin" id="sent_admin">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						  <tr>
							<td width="35%"><label for="login_form_user"><?php echo $login_label_user; ?></label></td>
							<td><input type="text" name="login_form_user" id="login_form_user" value="<?php if (isset($sysuser_username)) { echo $sysuser_username; } ?>" /></td>
						  </tr>
						  <tr>
							<td><label for="login_form_pass"><?php echo $login_label_pass; ?></label></td>
							<td><input type="password" name="login_form_pass" id="login_form_pass" /></td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center"><input type="submit" name="Submit" value="<?php echo $login_user_submit; ?>" class="boton" /></div></td>
						  </tr>
						</table>
					</form>
				</div>
				<div class="tab" id="tab_client">
					<form action="index.php" method="post" name="login_client" onsubmit="return validateclient(this);">
						<input type="hidden" name="sent_client" id="sent_client">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						  <tr>
							<td width="35%"><label for="login_form_client_user"><?php echo $login_label_user; ?></label></td>
							<td><input type="text" name="login_form_client_user" id="login_form_client_user" value="<?php if (isset($client_username)) { echo $client_username; } ?>" /></td>
						  </tr>
						  <tr>
							<td><label for="login_form_client_pass"><?php echo $login_label_pass; ?></label></td>
							<td><input type="password" name="login_form_client_pass" id="login_form_client_pass" /></td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center"><input type="submit" name="Submit" value="<?php echo $login_client_submit; ?>" class="boton" /></div></td>
						  </tr>
						</table>
					</form>
				</div>
			</div>

		</div>

	</div>

</div> <!-- main -->

	<div id="footer">
		<span><?php echo $copyright; ?> <?php echo date("Y") ?> | <a href="<?php echo $uri;?>" target="_blank"><?php echo $uri_txt;?></a></span>
	</div>

<script type="text/javascript">
var tabber1 = new Yetii({
id: 'login-tabs',
persist: true
});
</script>

</body>
</html>
<?php
	$database->Close();
	ob_end_flush();
?>