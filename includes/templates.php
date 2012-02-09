<?php
	function look_for_templates() {
		global $templates_ok;
		global $templates_error;
		$ignore = array('.', '..');
		$directory = './templates/';
		$files = glob($directory . "*");
		foreach($files as $file) {
			if(is_dir($file) && !in_array($file,$ignore)) {
				if(check_template_integrity($file)) {
					$folder = str_replace($directory,'',$file);
					// get template name
					$read_file = $file.'/template.php';
					$file_info = file($read_file);
					$name = (string)$file_info[3];
					if (empty($name)) {
						$name = '$file';
					}
					// generate the list
					$templates_ok[] = array(
										'folder' => $folder,
										'uri' => $file,
										'name' => $name
									);
				}
				else {
					$templates_error[] = array(
										'uri' => $file
									);
				}
			}
		}
		return $templates_ok;
	}
	
	function check_template_integrity($folder) {
		$required_files = array(
			'template.php',
			'main.css'
		);
		$miss = 0;
		$found = glob($folder . "/*");
		foreach ($required_files as $required) {
			$this_file = $folder.'/'.$required;
			if(!in_array($this_file,$found)) {
				$miss++;
			}
		}
		if($miss == 0) {
			return true;
		}
		unset($miss);
	}
?>