<?php
session_start();
require_once('header.php');

$database->MySQLDB();

$client = mysql_real_escape_string($_GET['client']);

$sql = $database->query('DELETE FROM tbl_clients WHERE client_user="' . $client .'"');
$sql = $database->query('DELETE FROM tbl_files WHERE client_user="' . $client .'"');

$dir = "./upload/" . $client . "/";
deleteall($dir);

$database->Close();

header("location:clients.php");
?>