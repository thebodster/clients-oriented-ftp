<?php
$allowed_levels = array(9);
require_once('includes/includes.php');
$page_title = __('Branding','cftp_admin');
include('header.php');
// lang vars
$select_logo_preview_1 = __("The picture on the right is not an actual representation of what they will see. The size on this preview is fixed, but remember that you can change the display size and picture quality for your client's pages on the",'cftp_admin');	
$select_logo_preview_2 = __("options",'cftp_admin');	
$select_logo_preview_3 = __("section.",'cftp_admin');	
?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>

	<div class="options_box whitebox">

<?php
if ($_POST) { // form sent?	
	
	/*
		Got part of this code from http://php.net/manual/es/function.move-uploaded-file.php, one of the user examples
		Also, checked this one for aid http://blog.brezovsky.net/en-text-4.html
	*/

	//  Valid file extensions (images)  
	$rEFileTypes =
	  "/^\.(jpg|jpeg|gif|png){1}$/i";
	$dir_base = "img/custom/logo/";
	
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

			$q = 'UPDATE tbl_options SET value="'.$safe_filename.'" WHERE name="logo_filename"';
			$sql = $database->query($q, $database->connection);
				$msg = __('The image was uploaded correctly.','cftp_admin');
				echo system_message('ok',$msg);
			?>
				<p><?php echo $logo_replace_info; ?></p>
				<p><?php echo $select_logo_preview_1; ?> <a href="options.php"><?php echo $select_logo_preview_2; ?></a> <?php echo $select_logo_preview_3; ?></p>
			<?php
		}
		else {
				$msg = __('The file you selected is not a valid image one. Please upload a jpg, gif or png formated logo picture.','cftp_admin');
				echo system_message('error',$msg);
			?>
				<p><?php echo $logo_replace_info; ?></p>
			<?php
		}
	}
	else {
			$msg = __('There was an error uploading the file. Please try again.','cftp_admin');
			echo system_message('error',$msg);
		?>
			<p><?php echo $logo_replace_info; ?></p>
		<?php
	}
}
else {
?>

	<script type="text/javascript">
		$(document).ready(function() {
			$("form").submit(function() {
				clean_form(this);

				is_complete(this.select_logo,'<?php _e('Please select an image file to upload','cftp_admin'); ?>');

				// show the errors or continue if everything is ok
				if (show_form_errors() == false) { return false; }
			});
		});
	</script>

		<p><?php _e('Use this page to upload your company logo, or update your current uploaded one. This image will be shown to your clients when they access their file list.','cftp_admin'); ?></p>

		<div id="current_logo">
			<div id="current_logo_left">
				<p><strong><?php _e('Current logo','cftp_admin'); ?></strong></p>
				<p class="logo_note"><?php echo $select_logo_preview_1; ?> <a href="options.php"><?php echo $select_logo_preview_2; ?></a> <?php echo $select_logo_preview_3; ?></p>
			</div>
			<div id="current_logo_right">
				<div id="current_logo_img">
					<img src="includes/thumb.php?src=../img/custom/logo/<?php echo LOGO_FILENAME; ?>&amp;w=220&amp;ql=<?php echo THUMBS_QUALITY; ?>&amp;type=tlogo" alt="" />
				</div>
			</div>
		</div>


		<div id="form_upload_logo">
			<form action="branding.php" name="logoupload" method="post" enctype="multipart/form-data">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000000">
				<ul class="form_fields">
					<li>
						<label><?php _e('Select image to upload','cftp_admin'); ?></label>
						<input type="file" name="select_logo" />
					</li>
					<li class="form_submit_li">
						<input type="submit" name="Submit" value="<?php _e('Upload','cftp_admin'); ?>" class="boton" />
					</li>
				</ul>
			</form>
		</div>

<?php } ?>

	</div>
	<div class="clear"></div>
</div>

<?php include('footer.php'); ?>