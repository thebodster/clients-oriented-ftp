<?php

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
	$actual_url = substr($pageURL,0,-8);
	return $actual_url;
}


function meassureimg($pic_source) {
	$picture = getimagesize($pic_source);
	$img_width = $the_picture[0];
	$img_height = $the_picture[1];

	if ($img_width > $img_height) {
		$use_meassure = 'h';
		$use_side = $max_logo_height;
	}
	else {
		$use_meassure = 'w';
		$use_side = $max_logo_width;
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