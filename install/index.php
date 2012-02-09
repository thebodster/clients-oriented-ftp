<?php
ob_start();
session_start();
header("Cache-control: private");
/*
	cFTP on Google Code
	http://code.google.com/p/clients-oriented-ftp/
	Distributed under GPL2
	Feel free to participate!
*/
require_once('../includes/sys.vars.php');
require_once('../includes/vars.php');
require_once('../includes/functions.php');

$database->MySQLDB();

function try_query($query) {
	if (empty($error_str)) {
		global $error_str;
	}
	foreach ($query as $i => $value) {
		$result = mysql_query($query[$i]);
		if (mysql_error()) {
			$error_str .= mysql_error().'<br />';
		}
	}
	return $result;
}

// collect data from form
$this_install_title = mysql_real_escape_string($_POST['this_install_title']);
$base_uri = mysql_real_escape_string($_POST['base_uri']);
$got_admin_name = mysql_real_escape_string($_POST['install_user_fullname']);
$got_admin_email = mysql_real_escape_string($_POST['install_user_mail']);
$got_admin_pass = mysql_real_escape_string(md5($_POST['install_user_pass']));
$got_admin_pass2 = mysql_real_escape_string(md5($_POST['install_user_repeat']));

require_once('../includes/form_validation_class.php');

// lang vars
$install_no_sitename = __('Sitename was not completed.','cftp_admin');
$install_no_baseuri = __('cFTP URI was not completed.','cftp_admin');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $page_title_install; ?> | <?php echo $short_system_name; ?></title>
<link rel="shortcut icon" href="favicon.ico" />
<link rel="stylesheet" media="all" type="text/css" href="../styles/base.css" />
<script src="../includes/js/js.validations.php" type="text/javascript"></script>
</head>

<body>

<div id="main">

	<div id="lonely_logo">
		<p><?php echo $full_system_name; ?></p>
	</div>
	<div class="clear"></div>

<?php
if ($_POST) {
	
	if ($base_uri{(strlen($base_uri) - 1)}!='/') { $base_uri .= '/'; }
	// begin form validation
	$valid_me->validate('completed',$this_install_title,$install_no_sitename);
	$valid_me->validate('completed',$base_uri,$install_no_baseuri);
	$valid_me->validate('completed',$got_admin_name,$validation_no_name);
	$valid_me->validate('completed',$got_admin_email,$validation_no_email);
	$valid_me->validate('completed',$_POST['install_user_pass'],$validation_no_pass);
	$valid_me->validate('completed',$_POST['install_user_repeat'],$validation_no_pass2);
	$valid_me->validate('email',$got_admin_email,$validation_invalid_mail);
	$valid_me->validate('length',$_POST['install_user_pass'],$validation_length_pass,MIN_USER_CHARS,MAX_USER_CHARS);
	$valid_me->validate('alpha',$_POST['install_user_pass'],$validation_alpha_pass);
	$valid_me->validate('pass_match','',$validation_match_pass,'','',$_POST['install_user_pass'],$_POST['install_user_repeat']);

	if ($valid_me->return_val) { //lets continue
		// call the file that creates the tables and fill it with the data we got previously
		include_once('database.php');
		if (!empty($error_str)) {
			$query_state = 'err';
		}
		else {
			$query_state = 'ok';
		}
	}

}
?>

	<div class="options_box whitebox" id="install_form">

<?php
	if(isset($valid_me)) { $valid_me->list_errors(); } // if the form was submited with errors, show them here
	
	if ($query_state == 'ok') {
	?>
		<div class="message message_ok"><p><?php _e('Congratulations! Everything is up and running.','cftp_admin'); ?></p></div>
		<p><?php _e('You may proceed to','cftp_admin'); ?> <a href="../index.php" target="_self"><?php _e('log in','cftp_admin'); ?></a> <?php _e('with your newely created user. Remember, the username for that account is <strong>admin</strong>.','cftp_admin'); ?></p>
	<?php
	}
	else if ($query_state == 'err') {
	?>
		<div class="message message_error">
			<p><?php _e('There seems to be an error. Please try again.','cftp_admin'); ?></p>
			<p><?php echo $error_str; ?></p>
		</div>
	<?php
	}
	else {
	?>
	
		<script type="text/javascript">
		
			window.onload = default_field;
	
			var js_err_sitename = "<?php echo $install_no_sitename; ?>"
			var js_err_baseuri = "<?php echo $install_no_baseuri; ?>"
			var js_err_name = "<?php echo $validation_no_name; ?>"
			var js_err_email = "<?php echo $validation_no_email; ?>"
			var js_err_pass = "<?php echo $validation_no_pass; ?>"
			var js_err_pass2 = "<?php echo $validation_no_pass2; ?>"
			var js_err_invalid_mail = "<?php echo $validation_invalid_mail; ?>"
			var js_err_pass_mismatch = "<?php echo $validation_match_pass; ?>"
			var js_err_pass_length = "<?php echo $validation_length_pass; ?>"
			var js_err_pass_chars = "<?php echo $validation_alpha_pass; ?>"
	
			function validateform(theform){
				is_complete(theform.this_install_title,js_err_sitename);
				is_complete(theform.base_uri,js_err_baseuri);
				is_complete(theform.install_user_fullname,js_err_name);
				is_complete(theform.install_user_mail,js_err_email);
				is_complete(theform.install_user_pass,js_err_pass);
				is_complete(theform.install_user_repeat,js_err_pass2);
				is_email(theform.install_user_mail,js_err_invalid_mail);
				is_length(theform.install_user_pass,<?php echo MIN_USER_CHARS; ?>,<?php echo MAX_USER_CHARS; ?>,js_err_pass_length);
				is_alpha(theform.install_user_pass,js_err_pass_chars);
				is_match(theform.install_user_pass,theform.install_user_repeat,js_err_pass_mismatch);
				// show the errors or continue if everything is ok
				if (error_list != '') {
					alert(error_title+error_list)
					error_list = '';
					return false;
				}
			}
	
		</script>
	
			<form action="index.php" name="installform" method="post" onsubmit="return validateform(this);">
	
				<h3><?php _e('Basic system options','cftp_admin'); ?></h3>
				<h4><?php _e("You need to provide this data for a correct system installation. The site name will be visible along the system panel, and the client's lists.<br />Don't forget to edit <em>/includes/sys.vars.php</em> with your database settings before installing.",'cftp_admin'); ?></h4>
				
				<label for="this_install_title"><?php _e('Site name','cftp_admin'); ?></label><input name="this_install_title" id="this_install_title" value="<?php echo $this_install_title; ?>" /><br />
				<label for="base_uri"><?php _e('cFTP URI (address)','cftp_admin'); ?></label><input name="base_uri" id="base_uri" value="<?php if ($base_uri) { echo $base_uri; } else { echo gettheurl();} ?>" /><br />
				
				<div class="options_divide"></div>
	
				<h3><?php _e('Default system administrator options','cftp_admin'); ?></h3>
				<h4><?php _e("This info will be appended to the user <em>admin</em>, which is the default system user. It can't be deleted (and in this version, it isn't editable yet, so please pick your password carefuly). Password should be between <strong>6 and 12 characters long</strong>.",'cftp_admin'); ?></h4>
				
				<label for="install_user_fullname"><?php _e('Full name','cftp_admin'); ?></label><input name="install_user_fullname" id="install_user_fullname" value="<?php echo $got_admin_name; ?>" /><br />
				<label for="install_user_mail"><?php _e('Admin e-mail','cftp_admin'); ?></label><input name="install_user_mail" id="install_user_mail" value="<?php echo $got_admin_mail; ?>" /><br />
	
				<label for="install_user_pass"><?php _e('Password','cftp_admin'); ?></label><input type="password" name="install_user_pass" id="install_user_pass" maxlength="12" /><br />
				<label for="install_user_repeat"><?php _e('Repeat','cftp_admin'); ?></label><input type="password" id="install_user_repeat" name="install_user_repeat" maxlength="12" /><br />
				
				<div align="right">
					<input type="submit" name="Submit" value="<?php _e('Install','cftp_admin'); ?>" class="boton" />
				</div>
	
				<div id="install_extra">
					<p><?php _e('After installing the system, you can go to the options page to set your timezone, prefered date display format and thubmnails parameters, besides being able to change the site options provided here.','cftp_admin'); ?></p>
				</div>
	
			</form>

		<?php } ?>

	</div>

</div> <!--main-->

<?php
	$database->Close();
	include('../footer.php');
?>