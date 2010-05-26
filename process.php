<?php
require_once('includes/vars.php');
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
			case 'logout':
				$this->logout();
			break;
		}
		$this->database->Close();
	}

	function delete_file() {
		if (isset($_GET['client']) && isset($_GET['id']) && isset($_GET['file'])) {
			$this->client = mysql_real_escape_string($_GET['client']);
			$this->id = mysql_real_escape_string($_GET['id']);
			$this->file = mysql_real_escape_string($_GET['file']);
			$this->sql = $this->database->query('DELETE FROM tbl_files WHERE client_user="' . $this->client .'" AND id="' . $this->id . '"');
			$this->gone = 'upload/' . $this->client .'/' . $this->file;
			delfile($this->gone);
			header("location:upload/" . $this->client . "/index.php");
		}
	}

	function delete_client() {
		if (isset($_GET['client'])) {
			$this->client = mysql_real_escape_string($_GET['client']);
			$this->sql = $this->database->query('DELETE FROM tbl_clients WHERE client_user="' . $this->client .'"');
			$this->sql = $this->database->query('DELETE FROM tbl_files WHERE client_user="' . $this->client .'"');
			$this->folder = "./upload/" . $this->client . "/";
			deleteall($this->folder);
			header("location:clients.php");
		}
	}

	function delete_user() {
		if (isset($_GET['user'])) {
			$this->user = mysql_real_escape_string($_GET['user']);
			$this->sql = $this->database->query('DELETE FROM tbl_users WHERE user="' . $this->user .'"');
			header("location:users.php");
		}
	}

	function logout() {
		header("Cache-control: private");
		unset($_SESSION['loggedin']);
		unset($_SESSION['access']);
		session_destroy();
		header("location:index.php");
	}
}

$process = new process;
?>