<?php
session_start();
ob_start();
/*
	cFTP on Google Code
	http://code.google.com/p/clients-oriented-ftp/
	Distributed under GPL2
	Feel free to participate!
*/
$allowed_enter = array(9,8,7);
require_once('includes/includes.php');
//if logged as a system user, go directly to home.php
if (in_session_or_cookies($allowed_enter)) {
	header("location:home.php");
}
check_for_client();
$database->MySQLDB();
// thanks to http://www.kminek.pl/lab/yetii/ for the tabs script!
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php _e('Log in','cftp_admin'); ?> | <?php echo $short_system_name; ?></title>
<link rel="shortcut icon" href="favicon.ico" />
<link rel="stylesheet" media="all" type="text/css" href="styles/base.css" />
<script type="text/javascript" src="includes/js/yetii-min.js"></script>
</head>

<body>
<div id="main">

<?php
if ($_POST) {
	$sysuser_username=mysql_real_escape_string($_POST['login_form_user']);
	$sysuser_password=mysql_real_escape_string(md5($_POST['login_form_pass']));
	$client_username=mysql_real_escape_string($_POST['login_form_client_user']);
	$client_password=mysql_real_escape_string(md5($_POST['login_form_client_pass']));

	if (isset($_POST['sent_admin'])) {
		// try to login as system user
		$sql = $database->query("SELECT * FROM tbl_users WHERE user='$sysuser_username' AND password='$sysuser_password'");
		$count=mysql_num_rows($sql);
		
		if($count>0){
			while($row = mysql_fetch_array($sql)) {
				$db_pass = $row['password'];
				$user_level = $row["level"];
			}
			if ($db_pass == $sysuser_password) {
				// changes here should also be reflected on header.php
				$_SESSION['loggedin'] = $sysuser_username;
				$_SESSION['access'] = 'admin';
				$_SESSION['userlevel'] = $user_level;
				// if remember me is on, set the cookie
				if ($_POST['login_form_remember']=='on') {
					setcookie("loggedin",$sysuser_username,time()+COOKIE_EXP_TIME);
					setcookie("password",$sysuser_password,time()+COOKIE_EXP_TIME);
					setcookie("access","admin",time()+COOKIE_EXP_TIME);
					setcookie("userlevel",$user_level,time()+COOKIE_EXP_TIME);
				}
				header("location:home.php");
			}
			else {
				$errorstate = 'admin_pass_wrong';
			}
		}
		else {
			$errorstate = 'admin_not_exists';
		}
	}
	elseif (isset($_POST['sent_client'])) {
		// try to login as client		
		$sql = $database->query("SELECT * FROM tbl_clients WHERE client_user='$client_username' AND password='$client_password'");
		$count=mysql_num_rows($sql);
		
		if($count>0){
			while($row = mysql_fetch_array($sql)) {
				$db_pass = $row['password'];
			}
			if ($db_pass == $client_password) {
				// changes here should also be reflected on templates
				$_SESSION['loggedin'] = $client_username;
				$_SESSION['access'] = $client_username;
				$_SESSION['userlevel'] = '0';
				// if remember me is on, set the cookie
				if ($_POST['login_form_client_remember']=='on') {
					setcookie("loggedin",$client_username,time()+COOKIE_EXP_TIME);
					setcookie("password",$client_password,time()+COOKIE_EXP_TIME);
					setcookie("access",$client_username,time()+COOKIE_EXP_TIME);
					setcookie("userlevel","0",time()+COOKIE_EXP_TIME);
				}
				header("location:upload/$client_username/");
			}
			else {
				$errorstate = 'client_pass_wrong';
			}
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
	
		<h3><?php _e('Log in','cftp_admin'); ?></h3>
		<h4><?php _e('Please select your appropiate account type below.','cftp_admin'); ?></h4>

		<div class="login_divide"></div>

		<?php
			// show login errors here.
			if (isset($errorstate)) {
				switch ($errorstate) {
				 case 'admin_not_exists':
				 	$login_err_message = __("The supplied username doesn't exist.",'cftp_admin');
				 break;
				 case 'client_not_exists':
				 	$login_err_message = __("The supplied username doesn't exist.",'cftp_admin');
				 break;
				 case 'admin_pass_wrong':
				 	$login_err_message = __("The supplied password is incorrect.",'cftp_admin');
				 break;
				 case 'client_pass_wrong':
				 	$login_err_message = __("The supplied password is incorrect.",'cftp_admin');
				 break;
				}
			?>
			<div class="message message_error" id="login_error">
				<p><?php echo $login_err_message; ?></p>
			</div>
		<?php } ?>

		<script src="includes/js/js.validations.php" type="text/javascript"></script>
	
		<script type="text/javascript">
		
			window.onload = default_field;
	
			var js_err_user = "<?php _e('Username was not completed','cftp_admin'); ?>"
			var js_err_pass = "<?php _e('Password was not completed','cftp_admin'); ?>"
	
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
				<li><a href="#tab_sysuser" id="tab_users"><?php _e('Administrator','cftp_admin'); ?></a></li>
				<li><a href="#tab_client" id="tab_clients"><?php _e('Client','cftp_admin'); ?></a></li>
			</ul>

			<div class="tabs-container">

				<div class="tab" id="tab_sysuser">
					<form action="index.php" method="post" name="login_admin" onsubmit="return validateadmin(this);">
						<input type="hidden" name="sent_admin" id="sent_admin">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						  <tr>
							<td width="35%"><label for="login_form_user"><?php _e('User','cftp_admin'); ?></label></td>
							<td><input type="text" name="login_form_user" id="login_form_user" value="<?php if (isset($sysuser_username)) { echo $sysuser_username; } ?>" class="field" /></td>
						  </tr>
						  <tr>
							<td><label for="login_form_pass"><?php _e('Password','cftp_admin'); ?></label></td>
							<td><input type="password" name="login_form_pass" id="login_form_pass" class="field" /></td>
						  </tr>
						  <tr>
							<td><label for="login_form_remember"><?php _e('Remember me','cftp_admin'); ?></label></td>
							<td><input type="checkbox" name="login_form_remember" id="login_form_remember" /></td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center"><input type="submit" name="Submit" value="<?php _e('Access Administrator','cftp_admin'); ?>" class="boton" /></div></td>
						  </tr>
						</table>
					</form>
				</div>
				<div class="tab" id="tab_client">
					<form action="index.php" method="post" name="login_client" onsubmit="return validateclient(this);">
						<input type="hidden" name="sent_client" id="sent_client">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						  <tr>
							<td width="35%"><label for="login_form_client_user"><?php _e('User','cftp_admin'); ?></label></td>
							<td><input type="text" name="login_form_client_user" id="login_form_client_user" value="<?php if (isset($client_username)) { echo $client_username; } ?>" class="field" /></td>
						  </tr>
						  <tr>
							<td><label for="login_form_client_pass"><?php _e('Password','cftp_admin'); ?></label></td>
							<td><input type="password" name="login_form_client_pass" id="login_form_client_pass" class="field" /></td>
						  </tr>
						  <tr>
							<td><label for="login_form_client_remember"><?php _e('Remember me','cftp_admin'); ?></label></td>
							<td><input type="checkbox" name="login_form_client_remember" id="login_form_client_remember" /></td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center"><input type="submit" name="Submit" value="<?php _e('Access file list','cftp_admin'); ?>" class="boton" /></div></td>
						  </tr>
						</table>
					</form>
				</div>
			</div>

		</div>

	</div>

</div> <!-- main -->

	<div id="footer">
		<span><?php _e('cFTP Free software (GPL2) | 2007 - ', 'cftp_admin'); ?> <?php echo date("Y") ?> | <a href="<?php echo $GLOBALS['uri'];?>" target="_blank"><?php echo $GLOBALS['uri_txt'];?></a></span>
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