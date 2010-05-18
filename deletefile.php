<?php

require_once('header.php');

$database->MySQLDB();

$client = $_GET['client'];
$id = $_GET['id'];
$file = $_GET['file'];

$sql = 'DELETE FROM tbl_files WHERE client_user="' . $client .'" AND id="' . $id . '"';
$result = mysql_query($sql);

$database->Close();

$gone = 'upload/' . $client .'/' . $file;
delfile($gone);

header("location:upload/" . $client . "/index.php");
	
?>