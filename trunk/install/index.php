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

// collect data from form
$this_install_title = mysql_real_escape_string($_POST['this_install_title']);
$base_uri = mysql_real_escape_string($_POST['base_uri']);
$got_admin_name = mysql_real_escape_string($_POST['install_user_fullname']);
$got_admin_email = mysql_real_escape_string($_POST['install_user_mail']);
$got_admin_pass = mysql_real_escape_string(md5($_POST['install_user_pass']));
$got_admin_pass2 = mysql_real_escape_string(md5($_POST['install_user_repeat']));

require_once('../includes/form_validation_class.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $page_title_install; ?> | <?php echo $short_system_name; ?></title>
<link rel="shortcut icon" href="favicon.ico" />
<link rel="stylesheet" media="all" type="text/css" href="../styles/base.css" />
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
		if ($success){
			$query_state = 'ok';
		}
		else {
			$query_state = 'err';
		}
	}

}
?>

	<div class="options_box whitebox" id="install_form">

<?php
	$valid_me->list_errors(); // if the form was submited with errors, show them here
	
	if ($query_state == 'ok') {
	?>
		<div class="message message_ok"><p><?php echo $install_ok; ?></p></div>
		<p><?php echo $install_ok2; ?></p>
	<?php
	}
	else if ($query_state == 'err') {
	?>
		<div class="message message_error">
			<p><?php echo $install_error; ?></p>	
		</div>
	<?php
	}
	else {
		include_once('../includes/js/js.validations.php'); ?>
	
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
	
				<h3><?php echo $install_general_title; ?></h3>
				<h4><?php echo $install_general_desc; ?></h4>
				
				<label for="this_install_title"><?php echo $options_site_name; ?></label><input name="this_install_title" id="this_install_title" value="<?php echo $this_install_title; ?>" /><br />
				<label for="base_uri"><?php echo $options_base_uri; ?></label><input name="base_uri" id="base_uri" value="<?php if ($base_uri) { echo $base_uri; } else { echo gettheurl();} ?>" /><br />
				
				<div class="options_divide"></div>
	
				<h3><?php echo $install_user_title; ?></h3>
				<h4><?php echo $install_user_desc; ?></h4>
				
				<label for="install_user_fullname"><?php echo $install_user_fullname; ?></label><input name="install_user_fullname" id="install_user_fullname" value="<?php echo $got_admin_name; ?>" /><br />
				<label for="install_user_mail"><?php echo $install_user_mail; ?></label><input name="install_user_mail" id="install_user_mail" value="<?php echo $got_admin_mail; ?>" /><br />
	
				<label for="install_user_pass"><?php echo $install_user_pass; ?></label><input type="password" name="install_user_pass" id="install_user_pass" maxlength="12" /><br />
				<label for="install_user_repeat"><?php echo $install_user_repeat; ?></label><input type="password" id="install_user_repeat" name="install_user_repeat" maxlength="12" /><br />
				
				<div align="right">
					<input type="submit" name="Submit" value="<?php echo $install_button; ?>" class="boton" />
				</div>
	
				<div id="install_extra">
					<?php echo $install_extra_info; ?>
				</div>
	
			</form>

		<?php } ?>

	</div>

</div> <!--main-->

<?php
	$database->Close();
	include('../footer.php');
?>