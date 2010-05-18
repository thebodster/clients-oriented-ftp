<?php
	session_start();
	$client = $_GET['client'];
	
	require_once('header.php');

	$database->MySQLDB();

	$sql2 = 'DELETE FROM tbl_clients WHERE client_user="' . $client .'"';
	$result = mysql_query($sql2);

	$sql = 'DELETE FROM tbl_files WHERE client_user="' . $client .'"';
	$result = mysql_query($sql);
	
	$dir = "./upload/" . $client . "/";
	deleteall($dir);

	$database->Close();
	
	header("location:clients.php");
?>