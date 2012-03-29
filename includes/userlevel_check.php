<?php
require_once('classes/database.php');
$database->MySQLDB();

function check_valid_cookie() {
	if (isset($_COOKIE['password']) && isset($_COOKIE['loggedin']) && isset($_COOKIE['userlevel'])) {
		$cookie_pass = mysql_real_escape_string($_COOKIE['password']);
		$cookie_user = mysql_real_escape_string($_COOKIE['loggedin']);
		$cookie_level = mysql_real_escape_string($_COOKIE['userlevel']);
		if($cookie_level == '0') {
			$sql_cookie = mysql_query("SELECT * FROM tbl_clients WHERE client_user='$cookie_user' AND password='$cookie_pass'");
		}
		else {
			$sql_cookie = mysql_query("SELECT * FROM tbl_users WHERE user='$cookie_user' AND password='$cookie_pass' AND level='$cookie_level'");
		}
		$count = mysql_num_rows($sql_cookie);
		if($count>0){
			return true;
		}
	}
}

// session check for the header. if no session or cookie is set, go to login
function check_for_session() {
	$is_logged_now = false;
	if (isset($_SESSION['loggedin'])) {
		$is_logged_now = true;
	}
	elseif ($_SESSION['access'] == 'admin') {
		$is_logged_now = true;
	}
	elseif (check_valid_cookie()) {
		$is_logged_now = true;
	}
	if(!$is_logged_now) {
		header("location:index.php");
	}
}

// check for admin users. this separetes system users from clietns
function check_for_admin() {
	$is_logged_admin = false;
	if ($_SESSION['access'] == 'admin') {
		$is_logged_admin = true;
	}
	elseif (check_valid_cookie() && mysql_real_escape_string($_COOKIE['access']) == 'admin') {
		$is_logged_admin = true;
	}
	if(!$is_logged_admin) {
		header("location:index.php");
	}
}

function check_for_client() {
	if (isset($_SESSION['userlevel']) || isset($_COOKIE['userlevel'])) {
		if ($_SESSION['userlevel'] == '0') {
			$client_username = $_SESSION['access'];
			header("location:upload/$client_username/");
		}
		elseif ($_COOKIE['userlevel'] == '0') {
			$client_username = $_COOKIE['access'];
			header("location:upload/$client_username/");
		}
	}
}

function can_see_content($allowed_levels) {
	$permission = false;
	if(isset($allowed_levels)) {
		/*
			we are doing 2 checks.
			first, we look for a cookie, and if it set, then get the
			associated userlevel to see if we are allowed to enter
			the page
		*/
		if (isset($_COOKIE['userlevel']) && in_array($_COOKIE['userlevel'],$allowed_levels)) { // gentleman, we have a cookie! userlevel is allowed
			$permission = true;
		}
		/*
			the second second check looks for a session, and if found
			see if the user level is among those defined by the page.
		*/
		if (isset($_SESSION['userlevel']) && in_array($_SESSION['userlevel'],$allowed_levels)) { // the page has desfined levels and we are included on them
			$permission = true;
		}
		/*
			after the checks, if the user is allowed, continue,
			else show the "not allowed message", then the footer, then die();
			so the actual page content is not shown.
		*/
	}
	if (!$permission) {
	?>
		<div id="main">
			<h2><?php _e('Access denied','cftp_admin'); ?></h2>
			<div class="whiteform whitebox">
				<?php
					$msg = __("Your user account doesn't allow you to view this page. Please contact a system administrator if you need to access this functions.",'cftp_admin');
					echo system_message('error',$msg);
				?>
			</div>
		</div>
		<?php
		include('footer.php');
		die();
	}
}
?>