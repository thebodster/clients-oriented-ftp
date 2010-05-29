<?php

// session check for the header. if no session or cookie is set, go to login
function check_for_session() {
	$is_logged_now = false;
	if (isset($_SESSION['loggedin'])) {
		$is_logged_now = true;
	}
	elseif ($_SESSION['access'] == 'admin') {
		$is_logged_now = true;
	}
	elseif ($_COOKIE['loggedin']) {
		$is_logged_now = true;
	}
	elseif ($_COOKIE['access'] == 'admin') {
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
	elseif ($_COOKIE['access'] == 'admin') {
		$is_logged_admin = true;
	}
	if(!$is_logged_admin) {
		header("location:index.php");
	}
}

function check_for_client() {
	if ($_SESSION['userlevel'] == '0') {
		$client_username = $_SESSION['access'];
		header("location:upload/$client_username/");
	}
	elseif ($_COOKIE['userlevel'] == '0') {
		$client_username = $_COOKIE['access'];
		header("location:upload/$client_username/");
	}
}

function can_see_content($allowed_levels,$page_title,$userlevel_not_allowed) {
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
		if (isset($_SESSION['userlevel']) && in_array($_SESSION['userlevel'],$allowed_levels)) { // the page has desfined levels and we are not included on them
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
			<h2><?php echo $page_title; ?></h2>
			<div class="whiteform whitebox">
				<div class="message message_error">
					<p><?php echo $userlevel_not_allowed; ?></p>
				</div>
			</div>
		</div>
		<?php
		include('footer.php');
		die();
	}
}
?>