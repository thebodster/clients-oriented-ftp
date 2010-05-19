<?php
session_start();
require_once('header.php');

$database->MySQLDB();

$user = mysql_real_escape_string($_GET['user']);

$sql = $database->query('DELETE FROM tbl_users WHERE user="' . $user .'"');

$database->Close();

header("location:users.php");
?>