<?php
/**
 * Class that handles the log out and file download actions.
 *
 * @package		ProjectSend
 */
$allowed_levels = array(9,8,7,0);
require_once('sys.includes.php');
require_once('header.php');

class process {
	function process() {
		$this->database = new MySQLDB;
		switch ($_GET['do']) {
			case 'download':
				$this->download_file();
			break;
			case 'logout':
				$this->logout();
			break;
		}
		$this->database->Close();
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