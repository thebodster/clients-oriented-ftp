<?php
require_once('header.php');

$database->MySQLDB();

$client = mysql_real_escape_string($_GET['client']);
$id = mysql_real_escape_string($_GET['id']);
$file = mysql_real_escape_string($_GET['file']);

$sql = $database->query('DELETE FROM tbl_files WHERE client_user="' . $client .'" AND id="' . $id . '"');

$database->Close();

$gone = 'upload/' . $client .'/' . $file;
delfile($gone);

header("location:upload/" . $client . "/index.php");
?>