<?php
// define language
$lang = SITE_LANG;
if(!isset($ld)) { $ld = 'cftp_admin'; }
require_once('../../includes/i18n.php');
I18n::LoadDomain("../../templates/".TEMPLATE_USE."/lang/{$lang}.mo", $ld);

$this_template = '../../templates/'.TEMPLATE_USE.'/'; 
include_once('../../templates/session_check.php');

$database->MySQLDB();
$sql = $database->query('SELECT * from tbl_files where client_user="' . $this_user .'"');
$sql2 = $database->query('SELECT * from tbl_clients where client_user="' . $this_user .'"');
while ($row = mysql_fetch_array($sql2)) {
	$user_full_name = $row['name'];
}
?>