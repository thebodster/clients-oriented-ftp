<?php
/*
if userlevel of this session is allowed, continue, else
show the "not allowed message", then the footer, then die();
so the actual page content is not shown
*/
if (isset($allowed_levels) && !in_array($_SESSION['userlevel'],$allowed_levels)) {
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