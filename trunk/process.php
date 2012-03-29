<?php
$allowed_levels = array(9,8,7,0);
require_once('includes/includes.php');
require_once('header.php');

class process {
	function process() {
		$this->database = new MySQLDB;
		switch ($_GET['do']) {
			case 'del_file':
				$this->delete_file();
			break;
			case 'del_client':
				$this->delete_client();
			break;
			case 'del_user':
				$this->delete_user();
			break;
			case 'download':
				$this->download_file();
			break;
			case 'logout':
				$this->logout();
			break;
		}
		$this->database->Close();
	}
	
	function delete_file() {
		$this->check_level = array(9,8);
		if (isset($_GET['client']) && isset($_GET['id']) && isset($_GET['file'])) {
			$this->client = mysql_real_escape_string($_GET['client']);
			$this->id = mysql_real_escape_string($_GET['id']);
			$this->file = mysql_real_escape_string($_GET['file']);
			// do a permissions check
			if (isset($this->check_level) && in_session_or_cookies($this->check_level)) {
				// delete from database
				$this->sql = $this->database->query('DELETE FROM tbl_files WHERE client_user="' . $this->client .'" AND id="' . $this->id . '"');
				// make the filename var
				$this->gone = 'upload/' . $this->client .'/' . $this->file;
				$this->thumb = 'upload/' . $this->client .'/thumbs/' . $this->file;
				delfile($this->gone);
				if (file_exists($this->thumb)) {
					delfile($this->thumb);
				}
			}
			header("location:upload/" . $this->client . "/index.php");
		}
	}

	function delete_client() {
		$this->check_level = array(9,8);
		if (isset($_GET['client'])) {
			$this->client = mysql_real_escape_string($_GET['client']);
			// do a permissions check
			if (isset($this->check_level) && in_session_or_cookies($this->check_level)) {
				$this->sql = $this->database->query('DELETE FROM tbl_clients WHERE client_user="' . $this->client .'"');
				$this->sql = $this->database->query('DELETE FROM tbl_files WHERE client_user="' . $this->client .'"');
				$this->folder = "./upload/" . $this->client . "/";
				deleteall($this->folder);
			}
			header("location:clients.php");
		}
	}

	function delete_user() {
		$this->check_level = array(9);
		if (isset($_GET['user'])) {
			$this->user = mysql_real_escape_string($_GET['user']);
			// do a permissions check
			if (isset($this->check_level) && in_session_or_cookies($this->check_level)) {
				$this->sql = $this->database->query('DELETE FROM tbl_users WHERE user="' . $this->user .'"');
			}
			header("location:users.php");
		}
	}

	function download_file() {
		$this->check_level = array(9,8,7,0);
		if (isset($_GET['file']) && isset($_GET['client'])) {
			// do a permissions check for logged in user
			if (isset($this->check_level) && in_session_or_cookies($this->check_level)) {

				$this->sql = $this->database->query('SELECT * FROM tbl_files WHERE url="' . $_GET['file'] .'"');
				$this->row = mysql_fetch_array($this->sql);
				$this->value = $this->row['download_count']+1;
				$this->sql2 = $this->database->query('UPDATE tbl_files SET download_count=' . $this->value .' WHERE url="' . $_GET['file'] .'"');

				$file = 'upload/'.$_GET['client'].'/'.$_GET['file'];
				header('Location: '.$file);
			}
		}
	}

	function logout() {
		header("Cache-control: private");
		unset($_SESSION['loggedin']);
		unset($_SESSION['access']);
		unset($_SESSION['userlevel']);
		session_destroy();
		// if there is a cookie, unset it
		setcookie("loggedin","",time()-COOKIE_EXP_TIME);
		setcookie("password","",time()-COOKIE_EXP_TIME);
		setcookie("access","",time()-COOKIE_EXP_TIME);
		setcookie("userlevel","",time()-COOKIE_EXP_TIME);
		header("location:index.php");
	}
}

$process = new process;
?>