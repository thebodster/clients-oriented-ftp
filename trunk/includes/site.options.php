<?php
// get options from db
$database->MySQLDB();

// get admin email address for upload notifications
$sql = $database->query('SELECT * FROM tbl_users WHERE user="admin"');
while($row = @mysql_fetch_array($sql)) {
	$admin_email_address = $row['email'];
}

// create array of options
$options_values = array();
$options = $database->query("SELECT * FROM tbl_options");
while ($row = @mysql_fetch_array($options)) {
	$options_values[$row['name']] = $row['value'];
}

// here we get the system options
$baseuri = $options_values['base_uri'];
$max_thumbnail_width = $options_values['max_thumbnail_width'];
$max_thumbnail_height = $options_values['max_thumbnail_height'];
$thumbnails_folder = $options_values['thumbnails_folder'];
$thumbnail_default_quality = $options_values['thumbnail_default_quality'];
$max_logo_width = $options_values['max_logo_width'];
$max_logo_height = $options_values['max_logo_height'];
$this_install_title = $options_values['this_install_title'];
$selected_clients_template = $options_values['selected_clients_template'];
$timezone = $options_values['timezone'];
$timeformat = $options_values['timeformat'];
$allowed_file_types = $options_values['allowed_file_types'];
$custom_logo_filename = $options_values['logo_filename'];
$site_lang = $options_values['site_lang'];

$database->Close();

date_default_timezone_set($timezone);
?>