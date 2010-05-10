<?php
	session_start();
	$user = $_GET['user'];
	
	require_once('includes/vars.php');
	require_once('includes/sys.vars.php');

	$sqllink = mysql_connect($host, $dbuser, $dbpass)or die('Cant connect to database');
	mysql_select_db($dbname)or die('Database not found');

	$sql = 'DELETE FROM tbl_users WHERE user="' . $user .'"';
	$result = mysql_query($sql);
	
	mysql_close($sqllink);
	
	header("location:users.php");
?>