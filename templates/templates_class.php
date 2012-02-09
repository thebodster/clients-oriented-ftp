<?php
	$required_files = array(
		'template.php',
		'main.css'
	);
	
	function look_for_templates() {
		$ignore = array('.', '..');
		$directory = @opendir('/');
		closedir($directory);
	}
	
	function check_template_integrity() {
	}
?>