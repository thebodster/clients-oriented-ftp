<?php
/*
	we are doing 2 checks.
	first, we look for a cookie, and if it set, then get the
	associated userlevel to see if we are allowed to enter
	the page
*/
if(isset($allowed_levels)) {
	if (isset($_COOKIE['userlevel']) && !in_array($_COOKIE['userlevel'],$allowed_levels)) { // gentleman, we have a cookie! but userlevel is not allowed
		$permission = false;
	}
	else {
		$permission = true;
	}
	/*
		second check: userlevel of this session
	*/
	if (isset($allowed_levels) && !in_array($_SESSION['userlevel'],$allowed_levels)) { // the page has desfined levels and we are not included on them
		$permission = false;
	}
	else {
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
?>