<?php

function in_session_or_cookies($levels) {
	if (in_array($_SESSION['userlevel'],$levels) || in_array($_COOKIE['userlevel'],$levels)) {
		return true;
	}
	else {
		return false;
	}
}

function mysql_real_escape_array($t){
	// nice function by brian on http://php.net/manual/es/function.mysql-real-escape-string.php
    return array_map("mysql_real_escape_string",$t);
}

function gettheurl() {
	// based on a script found on http://www.webcheatsheet.com/php/get_current_page_url.php
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
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

function getfilesize($dataarch) {
	if( $dataarch < 1024 ) {
		$tamfinal = $dataarch . " bytes";
		echo $tamfinal;
	}
	else if( $dataarch < 1024000 ) {
		$tamfinal = round( ( $dataarch / 1024 ), 1 ) . " kb";
		echo $tamfinal;
	}
	else {
		$tamfinal = round( ( $dataarch / 1024000 ), 1 ) . " mb";
		echo $tamfinal;
	}
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