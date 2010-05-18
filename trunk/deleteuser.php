<?php
	session_start();
	$user = $_GET['user'];
	
	require_once('includes/vars.php');
	require_once('includes/sys.vars.php');

	$database->MySQLDB();

	$sql = 'DELETE FROM tbl_users WHERE user="' . $user .'"';
	$result = mysql_query($sql);
	
	$database->Close();
	
	header("location:users.php");
?>