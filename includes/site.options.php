<?php
// get options from db
$database->MySQLDB();

// get admin email address for upload notifications
$sql = $database->query('SELECT * FROM tbl_users WHERE user="admin"');
while($row = @mysql_fetch_array($sql)) {
	define('ADMIN_EMAIL_ADDRESS',$row['email']);
}

// create array of options
$options_values = array();
$options = $database->query("SELECT * FROM tbl_options");
while ($row = @mysql_fetch_array($options)) {
	$options_values[$row['name']] = $row['value'];
}

$database->Close();

// here we get the system options
if(!empty($options_values)) {
	$allowed_file_types = $options_values['allowed_file_types'];
	
	define('BASE_URI',$options_values['base_uri']);
	define('THUMBS_MAX_WIDTH',$options_values['max_thumbnail_width']);
	define('THUMBS_MAX_HEIGHT',$options_values['max_thumbnail_height']);
	define('THUMBS_FOLDER',$options_values['thumbnails_folder']);
	define('THUMBS_QUALITY',$options_values['thumbnail_default_quality']);
	define('LOGO_MAX_WIDTH',$options_values['max_logo_width']);
	define('LOGO_MAX_HEIGHT',$options_values['max_logo_height']);
	define('LOGO_FILENAME',$options_values['logo_filename']);
	define('THIS_INSTALL_SET_TITLE',$options_values['this_install_title']);
	define('TEMPLATE_USE',$options_values['selected_clients_template']);
	define('TIMEZONE_USE',$options_values['timezone']);
	define('TIMEFORMAT_USE',$options_values['timeformat']);
	
	date_default_timezone_set(TIMEZONE_USE);
}
?>