<?php
/**
 * Common information used on all clients templates.
 * Avoids the need to define all of this when creating a new template.
 *
 * @package		ProjectSend
 * @subpackage	Templates
 */

/** Loads a language file from the current template folder based on the system options */
$lang = SITE_LANG;
if(!isset($ld)) { $ld = 'cftp_admin'; }
require_once('../../includes/i18n.php');
I18n::LoadDomain("../../templates/".TEMPLATE_USE."/lang/{$lang}.mo", $ld);

$this_template = '../../templates/'.TEMPLATE_USE.'/';
include_once('../../templates/session_check.php');

$database->MySQLDB();

/**
 * Gets the files list on a default query that can be used on the template.
 * Only files that are not marked as hidden are retrieved.
 */
$files_query = 'SELECT * FROM tbl_files WHERE client_user="' . $this_user .'" AND hidden=0';
$sql = $database->query($files_query);

/** Used to get the full name of the current client */
$sql2 = $database->query('SELECT * FROM tbl_clients WHERE client_user="' . $this_user .'"');
while ($row = mysql_fetch_array($sql2)) {
	$user_full_name = $row['name'];
}
?>