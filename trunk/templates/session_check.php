<?php
ob_start();
session_start();
header("Cache-control: private");
// check if user is logged in
if(!isset($_SESSION['loggedin'])) {
header("location:../../index.php");
}
// check if the logged user is an admin or the appropiate client
if ($_SESSION['access'] == 'admin') { $canview = 1; }
if ($_SESSION['access'] == $this_user) { $canview = 1; }
if (!isset($canview)) {
header("location:../../index.php");
}
	require_once('../../includes/vars.php');
	require_once('../../includes/sys.vars.php');
	require_once('../../includes/site.options.php');
	require_once('../../includes/functions.php');
?>