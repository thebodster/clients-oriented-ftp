<?php
$allowed_levels = array(9);
require_once('includes/includes.php');
$page_title = $page_title_logo;
include('header.php');
?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>

	<div id="current_logo" class="whitebox">
		<p><?php echo $current_logo; ?></p>
		<img src="includes/thumb.php?src=../img/custom/logo.jpg&amp;w=<?php echo $max_logo_width; ?>&amp;sh=1&amp;ql=<?php echo $thumbnail_default_quality; ?>&amp;type=tlogo" alt="" />
	</div>

	<div id="form_logo">

<?php
if ($_POST) { // form sent?	
	
	/*
		Got part of this code from http://php.net/manual/es/function.move-uploaded-file.php, one of the user examples
		Also, checked this one for aid http://blog.brezovsky.net/en-text-4.html
	*/

	//  Valid file extensions (images)  
	$rEFileTypes =
	  "/^\.(jpg|jpeg|gif|png){1}$/i";
	$dir_base = "img/custom/";
	
	$isFile = is_uploaded_file($_FILES['select_logo']['tmp_name']);
	if ($isFile) {
		//  sanatize file name
		//     - remove extra spaces/convert to _,
		//     - remove non 0-9a-Z._- characters,
		//     - remove leading/trailing spaces
		//  check file extension for legal file types
		$safe_filename = preg_replace(
						 array("/\s+/", "/[^-\.\w]+/"),
						 array("_", ""),
						 trim($_FILES['select_logo']['name']));
		if (preg_match($rEFileTypes, strrchr($safe_filename, '.'))) {
	
		  {$isMove = move_uploaded_file(
			$_FILES['select_logo']['tmp_name'],
			$dir_base.$safe_filename);}
	
			$the_picture = getimagesize($dir_base.$safe_filename);
			$img_width = $the_picture[0];
			$img_height = $the_picture[1];
	
			if (strtolower(substr($dir_base.$safe_filename, -3)) == "gif") { $source = @imagecreatefromgif($dir_base.$safe_filename); }
			if (strtolower(substr($dir_base.$safe_filename, -3)) == "jpg") { $source = @imagecreatefromjpeg($dir_base.$safe_filename); }
			if (strtolower(substr($dir_base.$safe_filename, -3)) == "pjpeg") { $source = @imagecreatefromjpeg($dir_base.$safe_filename); }
			if (strtolower(substr($dir_base.$safe_filename, -3)) == "jpeg") { $source = @imagecreatefromjpeg($dir_base.$safe_filename); }
			if (strtolower(substr($dir_base.$safe_filename, -3)) == "png") { $source = imagecreatefrompng($dir_base.$safe_filename); }
	
			$create_jpg = imagecreatetruecolor($img_width,$img_height);
			imagecopyresampled($create_jpg,$source,0,0,0,0,$img_width,$img_height,$the_picture[0],$the_picture[1]);
			imagejpeg($source,$dir_base.'logo.jpg','100');
			imagedestroy($source);
			
			delfile($dir_base.$safe_filename);
	
			echo '<div class="message message_ok"><p>'.$logo_uploaded_ok.'</p></div>';
			echo '<p>'.$logo_replace_info.'</p>';
			echo '<p>'.$select_logo_preview.'</p>';
		}
		else {
			echo '<div class="message message_error"><p>'.$logo_uploaded_filetye.'</p></div>';
			echo '<p>'.$logo_replace_info.'</p>';
		}
	}
	else {
		echo '<div class="message message_error"><p>'.$logo_uploaded_error.'</p></div>';
		echo '<p>'.$logo_replace_info.'</p>';
	}
}
else {
?>

	<?php include_once('includes/js/js.validations.php'); ?>

	<script type="text/javascript">

		var ja_file_err = "<?php echo $select_logo_file_err; ?>"

		function validateform(theform){
			is_complete_no_err(theform.select_logo);
			// show the errors or continue if everything is ok
			if (have_error != '') {
				alert(ja_file_err)
				have_error = '';
				return false;
			}
		}
	</script>

		<p><?php echo $logo_upload_description; ?></p>
		<div id="form_upload_logo" class="whitebox">
			<form action="" name="logoupload" method="post" enctype="multipart/form-data" onsubmit="return validateform(this);">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000000">
				<label><?php echo $logo_replace_file; ?></label>
				<input type="file" name="select_logo" />
				<div class="form_btns" align="right">
					<input type="submit" name="Submit" value="<?php echo $logo_upload_file; ?>" class="boton" />
				</div>
			</form>
		</div>
		<p><?php echo $logo_replace_info; ?></p>
		<p><?php echo $select_logo_preview; ?></p>

<?php } ?>

	</div>
</div>

<?php include('footer.php'); ?>