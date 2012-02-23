<?php
$allowed_update = array(9,8,7);
if (in_session_or_cookies($allowed_update)) {
	//header("location:home.php");
	
	$updates_made = 0;
	
	//r92 updates
	$new_database_values = array(
									'logo_filename' => 'logo.png',
									'site_lang' => 'en'
								);
	
	foreach($new_database_values as $row => $value) {
		$q = "SELECT * FROM tbl_options WHERE name = '$row'";
		$sql = $database->query($q);

		if(!mysql_num_rows($sql)) {
			$updates_made++;
			$qi = "INSERT INTO tbl_options (name, value) VALUES ('$row', '$value')";
			$sqli = $database->query($qi);
		}

	}


}
?>