<?php

require_once('header.php');

$sqllink = mysql_connect($host, $dbuser, $dbpass)or die('Cant connect to database');
mysql_select_db($dbname)or die('Database not found');

$client = $_GET['client'];
$id = $_GET['id'];
$file = $_GET['file'];

$sql = 'DELETE FROM tbl_files WHERE client_user="' . $client .'" AND id="' . $id . '"';
$result = mysql_query($sql);

mysql_close($sqllink);

$gone = 'upload/' . $client .'/' . $file;
delfile($gone);

header("location:upload/" . $client . "/index.php");
	
?>