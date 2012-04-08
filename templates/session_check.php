<?php
/**
 * Check for access permission to the files list.
 *
 * @package		ProjectSend
 * @subpackage	Templates
 */

ob_start();
session_start();
header("Cache-control: private");

/** This value comes from the index.php file located on each client's folder. */
$client_username = $this_user;

/**
 * Check the "access" session var or cookie that are set on the index.php file
 * when you log in correctly.
 */
if (isset($_SESSION['access']) && $_SESSION['access'] == 'admin') { $grant_access = 1; }
if (isset($_SESSION['access']) && $_SESSION['access'] == $this_user) { $grant_access = 1; }
if (isset($_COOKIE['access']) && $_COOKIE['access'] == 'admin') { $grant_access = 1; }
if (isset($_COOKIE['access']) && $_COOKIE['access'] == $client_username) { $grant_access = 1; }

/** If the info is not found, redirect to the log in page. */
if (!isset($grant_access)) {
	header("location:../../index.php");
	exit;
}

/** Continue loading the basic system files. */
require_once('../../includes/vars.php');
require_once('../../includes/sys.vars.php');
require_once('../../includes/site.options.php');
require_once('../../includes/functions.php');

?>