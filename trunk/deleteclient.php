<?php
	session_start();
	$client = $_GET['client'];
	
	require_once('header.php');

	$sqllink = mysql_connect($host, $dbuser, $dbpass)or die('Cant connect to database');
	mysql_select_db($dbname)or die('Database not found');

	$sql2 = 'DELETE FROM tbl_clients WHERE client_user="' . $client .'"';
	$result = mysql_query($sql2);

	$sql = 'DELETE FROM tbl_files WHERE client_user="' . $client .'"';
	$result = mysql_query($sql);
	
	$dir = "./upload/" . $client . "/";
	deleteall($dir);

	mysql_close($sqllink);
	
	header("location:clients.php");
?>