<?php
// define language
$lang = $site_lang;
define('I18N_DEFAULT_DOMAIN', $ld);
require_once('../../includes/i18n.php');
I18n::LoadDomain("../../templates/$selected_clients_template/lang/{$lang}.mo", $ld);

$this_template = '../../templates/'.$selected_clients_template.'/'; 
include_once('../../templates/session_check.php');

$database->MySQLDB();
$sql = $database->query('SELECT * from tbl_files where client_user="' . $this_user .'"');
$sql2 = $database->query('SELECT * from tbl_clients where client_user="' . $this_user .'"');
while ($row = mysql_fetch_array($sql2)) {
	$user_full_name = $row['name'];
}
?>