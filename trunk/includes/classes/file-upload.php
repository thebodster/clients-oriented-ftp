<?php

class Upload_File {

	var $folder;
	var $client;
	var $uploader;
	var $file;
	var $name;
	var $description;
	var $upload_state;

	function upload($arguments) {
		global $database;
		global $options_values;
		$this->folder = $arguments['folder'];
		$this->client = $arguments['client'];
		$this->uploader = $arguments['uploader'];
		$this->post_file = $arguments['file'];
		$this->name = $arguments['name'];
		$this->description = $arguments['description'];

		// CHECK: DOES FOLDER EXIST?
		if(!is_dir($this->folder)){
			$this->upload_state = 'folder_not_exists';
		}
		// CHECK: IS FILE UPLOADED?
		else {
			if(is_uploaded_file($this->post_file['tmp_name'])) {
				// CHECK: DOES FILE EXIST?
				if ($this->post_file['size'] > 0) {
					// CHECK: IS FILE TYPE ALLOWED?
					$allowed_file_types = str_replace(',','|',$options_values['allowed_file_types']);
					$file_types = "/^\.(".$allowed_file_types."){1}$/i";

					// Fix the filename
					$safe_filename = preg_replace(array("/\s+/", "/[^-\.\w]+/"), array("-", ""), trim($this->post_file['name']));
					if (preg_match($file_types, strrchr($safe_filename, '.'))) {
						// Make the final filename using timestamp + sanitized name
						$file_final_name = time().'-'.$safe_filename;
						$path = $this->folder.$file_final_name;
						// Try to upload
						if (move_uploaded_file($this->post_file['tmp_name'], $path)) {
							// Create MySQL entry if the file was uploaded correctly
							$timestampdate = time();
							$result = $database->query("INSERT INTO tbl_files (id,url,filename,description,client_user,timestamp,uploader)"
							."VALUES ('NULL', '$file_final_name', '$this->name', '$this->description', '$this->client', '$timestampdate', '$this->uploader')");
							$this->upload_state = 'ok';
						}
						else {
							// could not move file
							$this->upload_state = 'err_move';
						}
					}
					else {
						// filetype isn't allowed
						$this->upload_state = 'err_type';
					}
				}
				else {
					// file doesn't exist anymore
					$this->upload_state = 'err_exist';
				}
			}
			else {
				$this->upload_state = 'err';
			}
		}
		return $this->upload_state;
	}	
}
?>