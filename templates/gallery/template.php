<?php
/*
Template name:
Gallery

Background modified from: http://www.artofadambetts.com/weblog/2008/05/black-leather-apple-desktop-background/
Delete icon: http://www.iconfinder.com/icondetails/37519/16/can_delete_trash_icon
*/

$ld = 'cftp_template_gallery'; // specify the language domain for this template
include_once('../../templates/common.php'); // include the required functions for every template

$window_title = __('Gallery','cftp_template_gallery');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $user_full_name.' | '.$window_title; ?> | <?php echo $short_system_name; ?></title>
<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this_template; ?>main.css" />
<link rel="shortcut icon" href="../../favicon.ico" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<link href='http://fonts.googleapis.com/css?family=Sirin+Stencil' rel='stylesheet' type='text/css'>
</head>

<body>

<script type="text/javascript">
	function confirm_file_delete() {
		if (confirm("<?php _e('This will delete the file permanently. Continue?','cftp_template_gallery'); ?>")) return true ;
		else return false ;
	}

	var xhr;
	function startAjax() {
		if(window.XMLHttpRequest) {
			xhr = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	function addDownloadCount(fileid) {
		startAjax();
		xhr.open("GET","../../process.php?do=add_download_count&file="+fileid);
		xhr.onreadystatechange = callback;
		xhr.send(null);
	}

	// i'm leaving this here for future error handling
	function callback() {
		if (xhr.readyState == 4) {
			if (xhr.status == 200) {
			}
		}
	}
</script>

<div id="header">

	<?php if (file_exists('../../img/custom/logo/'.$custom_logo_filename)) { ?>
		<div id="current_logo">
			<img src="../../includes/thumb.php?src=../img/custom/logo/<?php echo $custom_logo_filename; ?>&amp;w=<?php echo $max_logo_width; ?>&amp;type=tlogo" alt="" />
		</div>
	<?php } ?>
</div>
	
<div id="content">

	<div class="wrapper">

<?php
	$count=mysql_num_rows($sql);
	if (!$count) {
		_e('There are no files.','cftp_template_gallery');
	}
	else {
?>
		<ul class="photo_list">
		<?php
			while($row = mysql_fetch_array($sql)) {
		?>
			<?php
				$extension = strtolower(substr($row['url'], -3));
				$img_formats = array('gif','jpg','pjpeg','jpeg','png');
				if (in_array($extension,$img_formats)) {
			?>
					<li>
						<h5><?php echo htmlentities($row['filename']); ?></h5>
						<div class="img_prev">
							<a href="../../process.php?do=download&amp;client=<?php echo $this_user; ?>&amp;file=<?php echo $row['url']; ?>" target="_blank" onclick="addDownloadCount(<?php echo $row['id']; ?>);">
								<img src="../../includes/thumb.php?src=../upload/<?php echo $this_user; ?>/<?php echo $row['url']; ?>&amp;w=280&amp;gr=1&amp;type=prev&amp;who=<?php echo $this_user; ?>&amp;name=<?php echo $row['url']; ?>" class="thumbnail" alt="" />
							</a>
						</div>
						<div class="img_data">
							<div class="download_link">
								<a href="../../process.php?do=download&amp;client=<?php echo $this_user; ?>&amp;file=<?php echo $row['url']; ?>" target="_blank" onclick="addDownloadCount(<?php echo $row['id']; ?>);">
									<?php _e('Download original','cftp_template_gallery'); ?>
								</a>
							</div>
							<div class="img_actions">
								<?php
									// show DELETE FILE only to users, not clients
									$clients_allowed = array(9,8,7);
									if (in_session_or_cookies($clients_allowed)) {
								?>
										<a onclick="return confirm_file_delete();" href="../../process.php?do=del_file&amp;client=<?php echo $this_user; ?>&amp;id=<?php echo $row['id']; ?>&amp;file=<?php echo $row['url']; ?>" target="_self">
											<img src="<?php echo $this_template; ?>img/delete.png" alt="<?php _e('Delete','cftp_template_gallery'); ?>" />
										</a>
								<?php
									}
								?>
							</div>
						</div>
					</li>
				<?php
				}
			}
			?>
		</ul>
	<?php
	}
	?>

	</div>
</div>

<div id="footer">
	<span><?php _e('cFTP Free software (GPL2) | 2007 - ', 'cftp_template_gallery'); ?> <?php echo date("Y") ?> | <a href="<?php echo $GLOBALS['uri'];?>" target="_blank"><?php echo $GLOBALS['uri_txt'];?></a></span>
</div>

</body>
</html>
<?php $database->Close(); ?>