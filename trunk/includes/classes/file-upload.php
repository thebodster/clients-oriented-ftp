<?php

class PSend_Upload_File {

	var $folder;
	var $client;
	var $uploader;
	var $file;
	var $name;
	var $description;
	var $upload_state;
	var $separator = '_';
	
	function is_filetype_allowed($filename) {
		global $options_values;
		$this->safe_filename = $filename;
		$allowed_file_types = str_replace(',','|',$options_values['allowed_file_types']);
		$file_types = "/^\.(".$allowed_file_types."){1}$/i";
		if (preg_match($file_types, strrchr($this->safe_filename, '.'))) {
			return true;
		}
	}
	
	function safe_rename($name) {
		$this->name = $name;
		$this->safe_filename = preg_replace('/[^\w\._]+/', $this->separator, $this->name);
		return $this->safe_filename;
	}
	
	function safe_rename_on_disc($name,$folder) {
		$this->name = $name;
		$this->folder = $folder;
		$this->new_filename = preg_replace('/[^\w\._]+/', $this->separator, $this->name);
		if(rename($this->folder.'/'.$this->name, $this->folder.'/'.$this->new_filename)) {
			return $this->new_filename;
		}
		else {
			return false;
		}
	}
	
	function upload_move($arguments) {
		$this->uploaded_name = $arguments['uploaded_name'];
		$this->folder = $arguments['move_to_folder'];
		$this->filename = $arguments['filename'];

		$this->file_final_name = time().'-'.$this->filename;
		$this->path = $this->folder.$this->file_final_name;
		if (move_uploaded_file($this->uploaded_name, $this->path)) {
			return true;
		}
	}

	function upload_copy($arguments) {
		$this->uploaded_name = $arguments['uploaded_name'];
		$this->folder = $arguments['move_to_folder'];
		$this->filename = $arguments['filename'];

		$this->file_final_name = time().'-'.$this->filename;
		$this->path = $this->folder.$this->file_final_name;
		if (copy($this->uploaded_name, $this->path)) {
			unlink($this->uploaded_name);
			return $this->file_final_name;
		}
		else {
			return false;
		}
	}
	
	function upload_add_to_database($arguments) {
		global $database;
		$this->post_file = $arguments['file'];
		$this->name = $arguments['name'];
		$this->description = $arguments['description'];
		$this->client = $arguments['client'];
		$this->uploader = $arguments['uploader'];
		$this->timestamp = time();
		$result = $database->query("INSERT INTO tbl_files (id,url,filename,description,client_user,timestamp,uploader)"
		."VALUES ('NULL', '$this->post_file', '$this->name', '$this->description', '$this->client', '$this->timestamp', '$this->uploader')");
		if(!empty($result)) {
			return true;
		}
		else {
			return false;
		}
	}

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