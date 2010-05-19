<?php
	ob_start();
	session_start();

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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>cFTP | <?php echo $basictitle; ?></title>
<link rel="shortcut icon" href="favicon.ico" />
<link rel="stylesheet" media="all" type="text/css" href="styles/base.css" />
</head>

<body>

<div id="main">

<?php
if ($_POST) {

	$database->MySQLDB();
	
	$username=mysql_real_escape_string($_POST['usuario']);
	$password=mysql_real_escape_string(md5($_POST['password']));
	
	$sql = $database->query("SELECT * FROM tbl_users WHERE user='$username' and password='$password'");
	$count=mysql_num_rows($sql);
	
	if($count>0){
		session_register("usuario");
		session_register("password");
		header("location:home.php");
	}
	else {
	?>
		<div class="message message_error" id="login_error">
			<p><?php echo $login_err.' | '.$login_err2; ?></p>
		</div>
	<?php
	}

	$database->Close();

}
?>
	
	<div id="lonely_logo">
		<p>cFTP (clients-oriented-ftp)</p>
	</div>
	<div class="whiteform whitebox" id="loginform">
	
	<form action="" method="post" name="login" target="_self">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td><label for="usuario"><?php echo $askuser; ?></label>&nbsp;</td>
			<td><input type="text" name="usuario" id="usuario" />&nbsp;</td>
		  </tr>
		  <tr>
			<td><label for="password"><?php echo $askpass; ?></label>&nbsp;</td>
			<td><input type="password" name="password" id="password" /></td>
		  </tr>
		  <tr>
			<td colspan="2"><div align="center"><input type="submit" name="Submit" value="<?php echo $btnlogin; ?>" class="boton" style="margin-top:30px; width:100px;" /></div></td>
		  </tr>
		</table>
	</form>

	</div>

</div>

	<div id="footer">
		<span><?php echo $copyright; ?> <?php echo date("Y") ?> | <a href="<?php echo $uri;?>" target="_blank"><?php echo $uri_txt;?></a></span>
	</div>

</body>
</html>
<?php ob_end_flush(); ?>