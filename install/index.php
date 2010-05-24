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
	require_once('../includes/vars.php');
	require_once('../includes/sys.vars.php');
	require_once('../includes/functions.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>cFTP | <?php echo $basictitle; ?></title>
<link rel="shortcut icon" href="favicon.ico" />
<link rel="stylesheet" media="all" type="text/css" href="../styles/base.css" />
</head>

<body>

<div id="main">

	<div id="lonely_logo">
		<p>cFTP (clients-oriented-ftp)</p>
	</div>
	<div class="clear"></div>

<?php
if ($_POST) {
?>
	<div id="install_inside">
<?php
	$database->MySQLDB();

	// collect data from form
	$this_install_title = mysql_real_escape_string($_POST['this_install_title']);
	$base_uri = mysql_real_escape_string($_POST['base_uri']);
	$got_admin_name = mysql_real_escape_string($_POST['install_user_fullname']);
	$got_admin_email = mysql_real_escape_string($_POST['install_user_mail']);
	$got_admin_pass = mysql_real_escape_string(md5($_POST['install_user_pass']));
	
	// call the file that creates the tables and fill it with the data we got previously
	include_once('database.php');

	$database->Close();
?>
	</div>
<?php
}
else {
?>
	<script type="text/javascript">
		var missed_fields = "<?php echo $install_missed_data; ?>"
		var invalid_mail = "<?php echo $install_invalid_mail; ?>"
		var pass_mismatch = "<?php echo $install_pass_mismatch; ?>"
		var pass_short = "<?php echo $install_pass_short; ?>"
		var pass_chars = "<?php echo $install_pass_chars; ?>"

		function validinstall(){
			// all complete validations
			if (
				document.installform.this_install_title.value.length==0 ||
				document.installform.base_uri.value.length==0 ||
				document.installform.install_user_fullname.value.length==0 ||
				document.installform.install_user_mail.value.length==0 ||
				document.installform.install_user_pass.value.length==0 ||
				document.installform.install_user_repeat.value.length==0
			) {
				alert(missed_fields)
				return false;
			}

			// onto email validation now
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			var address = document.installform.install_user_mail.value;
			if (reg.test(address) == false) {
				alert(invalid_mail);
				return false;
			}
			
			// short or long passwd
			if (document.installform.install_user_pass.value.length < 6 || document.installform.install_user_pass.value.length > 12) {
				alert(pass_short)
				return false;
			}
			
			// alphanumeric check for password
			var numaric = document.installform.install_user_pass.value;
			if (!(numaric.match(/^[a-zA-Z0-9]+$/)))
			  {
				alert(pass_chars);
				return false;
			}

			
			// password matching validation
			if (document.installform.install_user_pass.value != document.installform.install_user_repeat.value) {
				alert(pass_mismatch)
				return false;
			}
			
			// everything went ok! congratulations!
			document.forms[0].submit();
		}
	</script>

	<div class="options_box whitebox" id="install_form">
		<form action="" name="installform" method="post" target="_self">

			<h3><?php echo $install_general_title; ?></h3>
			<h4><?php echo $install_general_desc; ?></h4>
			
			<label for="this_install_title"><?php echo $options_site_name; ?></label><input name="this_install_title" id="this_install_title" /><br />
			<label for="base_uri"><?php echo $options_base_uri; ?></label><input name="base_uri" id="base_uri" value="<?php echo gettheurl();?>" /><br />
			
			<div class="options_divide"></div>

			<h3><?php echo $install_user_title; ?></h3>
			<h4><?php echo $install_user_desc; ?></h4>
			
			<label for="install_user_fullname"><?php echo $install_user_fullname; ?></label><input name="install_user_fullname" id="install_user_fullname" /><br />
			<label for="install_user_mail"><?php echo $install_user_mail; ?></label><input name="install_user_mail" id="install_user_mail" /><br />

			<label for="install_user_pass"><?php echo $install_user_pass; ?></label><input type="password" name="install_user_pass" id="install_user_pass" maxlength="12" /><br />
			<label for="install_user_repeat"><?php echo $install_user_repeat; ?></label><input type="password" id="install_user_repeat" name="install_user_repeat" maxlength="12" /><br />
			
			<div align="right">
				<input type="button" name="Submit" value="<?php echo $install_button; ?>" class="boton" onclick="validinstall();" />
			</div>

			<div id="install_extra">
				<?php echo $install_extra_info; ?>
			</div>

		</form>
	</div>

<?php } ?>

</div> <!--main-->

<div id="footer">
	<span><?php echo $copyright; ?> <?php echo date("Y") ?> | <a href="<?php echo $uri;?>" target="_blank"><?php echo $uri_txt;?></a></span>
</div>

</body>
</html>
<?php ob_end_flush(); ?>