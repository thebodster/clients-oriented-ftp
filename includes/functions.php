<?php

function get_client_information($client) {
	global $database;
	$get_client_info = $database->query("SELECT * FROM tbl_clients WHERE id='$client'");
	while ($row = mysql_fetch_assoc($get_client_info)) {
		$information = array(
							'name' => $row['name'],
							'username' => $row['client_user'],
							'address' => $row['address'],
							'phone' => $row['phone'],
							'email' => $row['email'],
							'notify' => $row['notify'],
							'contact' => $row['contact'],
							'created_date' => $row['timestamp'],
							'created_by' => $row['created_by']
						);
		if(!empty($information)) {
			return $information;
		}
		else {
			return false;
		}
	}
}

function get_client_by_id($client) {
	global $database;
	$get_client_info = $database->query("SELECT * FROM tbl_clients WHERE client_user='$client'");
	while ($row = mysql_fetch_assoc($get_client_info)) {
		$information = array(
							'id' => $row['id'],
							'name' => $row['name'],
							'address' => $row['address'],
							'phone' => $row['phone'],
							'email' => $row['email'],
							'notify' => $row['notify'],
							'contact' => $row['contact'],
							'created_date' => $row['timestamp'],
							'created_by' => $row['created_by']
						);
		if(!empty($information)) {
			return $information;
		}
		else {
			return false;
		}
	}
}

function check_if_notify_client($client) {
	global $database;
	$get_notify = $database->query("SELECT notify, email FROM tbl_clients WHERE client_user='$client'");
	while ($row = mysql_fetch_assoc($get_notify)) {
		if($row['notify'] === '1') {
			return $row['email'];
		}
		else {
			return false;
		}
	}
}

function default_footer_info() {
?>
	<div id="footer">
			<span><?php _e('ProjectSend Free software (GPL2) | 2007 - ', 'cftp_admin'); ?> <?php echo date("Y") ?> | <a href="<?php echo SYSTEM_URI; ?>" target="_blank"><?php echo SYSTEM_URI_LABEL; ?></a></span>
	</div>
<?php
}

function message_no_clients() {
?>
	<div class="whitebox whiteform whitebox_text">
		<p><?php _e('There are no clients at the moment', 'cftp_admin'); ?></p>
		<p><a href="clientform.php" target="_self"><?php _e('Create a new one', 'cftp_admin'); ?></a> <?php _e('to be able to upload files for that account.', 'cftp_admin'); ?></p>
	</div>
<?php
}

function system_message($type,$message,$div_id = '') {
	/*
		Current CSS available message classes:
		- message_ok
		- message_error
		- message_info
	*/	
	$return = '<div class="message message_'.$type.'"';
	if (isset($div_id) && $div_id != '') {
		$return .= ' id="'.$div_id.'"';
	}
	$return .= '>'.$message.'</div>';
	// Output
	return $return;
}

function in_session_or_cookies($levels) {
	if (isset($_SESSION['userlevel']) || isset($_COOKIE['userlevel'])) {
		if (in_array($_SESSION['userlevel'],$levels) || in_array($_COOKIE['userlevel'],$levels)) {
			return true;
		}
		else {
			return false;
		}
	}
}

function get_current_user_level() {
	if (isset($_SESSION['userlevel'])) {
		$l = $_SESSION['userlevel'];
	}
	elseif (isset($_COOKIE['userlevel'])) {
		$l = $_COOKIE['userlevel'];
	}
	return $l;
}

function get_current_user_username() {
	if (isset($_COOKIE['loggedin'])) {
		$u = $_COOKIE['loggedin'];
	}
	elseif (isset($_SESSION['loggedin'])) {
		$u = $_SESSION['loggedin'];
	}
	return $u;
}

function mysql_real_escape_array($t){
	// nice function by brian on http://php.net/manual/es/function.mysql-real-escape-string.php
    return array_map("mysql_real_escape_string",$t);
}

function gettheurl() {
	// based on a script found on http://www.webcheatsheet.com/php/get_current_page_url.php
	$pageURL = 'http';
	if (!empty($_SERVER['HTTPS'])) {if($_SERVER['HTTPS'] == 'on'){$pageURL .= "s";}}
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	// check if we are accesing the install folder or the index.php file directly
	$extension = substr($pageURL,-4);
	if ($extension=='.php') {
		$pageURL = substr($pageURL,0,-17);
		return $pageURL;
	}
	else {
		$pageURL = substr($pageURL,0,-8);
		return $pageURL;
	}
}

function format_file_size($size) {
	if( $size < 1024 ) {
		$format_size = $size . " bytes";
	}
	else if( $size < 1024000 ) {
		$format_size = round( ( $size / 1024 ), 1 ) . " kb.";
	}
	else {
		$format_size = round( ( $size / 1024000 ), 1 ) . " mb.";
	}
	return $format_size;
}

function delfile($curfile)
{
	chmod($curfile, 0777);
	unlink($curfile);
}

function deleteall($dir)
{
	if (is_dir($dir)) {
	   if ($dh = opendir($dir)) {
		   while (($file = readdir($dh)) !== false ) {
				if( $file != "." && $file != ".." )
				{
						if( is_dir( $dir . $file ) )
						{
								deleteall( $dir . $file . "/" );
								rmdir( $dir . $file );
						}
						else
						{
								unlink( $dir . $file );
						}
				}
		   }
		   closedir($dh);
		   rmdir($dir);
	   }
	}
}

?>