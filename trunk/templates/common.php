<?php
// define language
$lang = SITE_LANG;
if(!isset($ld)) { $ld = 'cftp_admin'; }
require_once('../../includes/i18n.php');
I18n::LoadDomain("../../templates/".TEMPLATE_USE."/lang/{$lang}.mo", $ld);

$this_template = '../../templates/'.TEMPLATE_USE.'/'; 
include_once('../../templates/session_check.php');

$database->MySQLDB();

$files_query = 'SELECT * FROM tbl_files WHERE client_user="' . $this_user .'" AND hidden=0';

$sql = $database->query($files_query);
$sql2 = $database->query('SELECT * FROM tbl_clients WHERE client_user="' . $this_user .'"');
while ($row = mysql_fetch_array($sql2)) {
	$user_full_name = $row['name'];
}
?>