<?php
/**
 * Check if the system is installed already. If not, redirect to the
 * installation form.
 *
 * @package ProjectSend
 */
$database->MySQLDB();

$tables_missing = 0;
/**
 * This table list is defined on sys.vars.php
 */
foreach ($current_tables as $table) {
	$this_table = $database->query("SHOW TABLES LIKE '$table'");
	if (mysql_num_rows($this_table) == 0) {
		$tables_missing++;
	}
}

if ($tables_missing > 0) {
	header("Location:install/index.php");
	exit;
}
?>