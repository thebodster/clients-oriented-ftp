<?php
$allowed_levels = array(9);
require_once('includes/includes.php');
$page_title = __('Logo configuration','cftp_admin');
include('header.php');
// lang vars
$logo_replace_info = __("The new image will act as your logo across your client's file lists.",'cftp_admin');
$select_logo_preview_1 = __("The picture on the left is not an actual representation of what they will see. The size on this preview is fixed, but remember that you can change the display size and picture quality for your client's pages on the",'cftp_admin');	
$select_logo_preview_2 = __("options",'cftp_admin');	
$select_logo_preview_3 = __("section.",'cftp_admin');	
?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>

	<div id="current_logo" class="whitebox">
		<p><?php _e('Current logo:','cftp_admin'); ?></p>
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
			?>
				<div class="message message_ok"><p><?php _e('The image was uploaded correctly.','cftp_admin'); ?></p></div>
				<p><?php echo $logo_replace_info; ?></p>
				<p><?php echo $select_logo_preview_1; ?> <a href="options.php"><?php echo $select_logo_preview_2; ?></a> <?php echo $select_logo_preview_3; ?></p>
			<?php
		}
		else {
			?>
				<div class="message message_error"><p><?php _e('The file you selected is not a valid image one. Please upload a jpg, gif or png formated logo picture.','cftp_admin'); ?></p></div>
				<p><?php echo $logo_replace_info; ?></p>
			<?php
		}
	}
	else {
		?>
			<div class="message message_error"><p><?php _e('There was an error uploading the file. Please try again.','cftp_admin'); ?></p></div>
				<p><?php echo $logo_replace_info; ?></p>
		<?php
	}
}
else {
?>

	<script type="text/javascript" src="includes/js/js.validations.php"></script>

	<script type="text/javascript">

		var ja_file_err = "<?php _e('Please select an image file to upload','cftp_admin'); ?>"

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

		<p><?php _e('Use this page to upload your company logo, or update your current uploaded one. This image will be shown to your clients when they access their file list.','cftp_admin'); ?></p>
		<div id="form_upload_logo" class="whitebox">
			<form action="" name="logoupload" method="post" enctype="multipart/form-data" onsubmit="return validateform(this);">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000000">
				<label><?php _e('Select image to upload','cftp_admin'); ?></label>
				<input type="file" name="select_logo" />
				<div class="form_btns" align="right">
					<input type="submit" name="Submit" value="<?php _e('Upload','cftp_admin'); ?>" class="boton" />
				</div>
			</form>
		</div>
		<p><?php echo $logo_replace_info; ?></p>
		<p><?php echo $select_logo_preview_1; ?> <a href="options.php"><?php echo $select_logo_preview_2; ?></a> <?php echo $select_logo_preview_3; ?></p>

<?php } ?>

	</div>
</div>

<?php include('footer.php'); ?>