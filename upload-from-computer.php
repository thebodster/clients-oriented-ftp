<?php
$allowed_levels = array(9,8,7);
require_once('includes/includes.php');
$page_title = __('Upload files', 'cftp_admin');
include('header.php');

$database->MySQLDB();
?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>
	
	<?php
		// count clients to show error or form
		$sql = $database->query("SELECT * FROM tbl_clients");
		$count = mysql_num_rows($sql);
		if (!$count) {
			// Echo the no clients default message
			message_no_clients();
		}
		else { 
	?>
			<p>
				<?php
					_e('Click on Add files to select all the files that you want to upload, and then click continue. On the next step, you will be able to set a name and description for each uploaded file. Remember that the maximum allowed file size (in mb.) is ','cftp_admin');
					echo '<strong>'.MAX_FILESIZE.'</strong>.';
				?>
			</p>

			<style type="text/css">@import url(includes/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css);</style>
			<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
			<script type="text/javascript" src="includes/plupload/js/plupload.full.js"></script>
			<script type="text/javascript" src="includes/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js"></script>

			<?php
				if(SITE_LANG != 'en') {
					$plupload_lang_file = 'includes/plupload/js/i18n/'.SITE_LANG.'.js';
					if(file_exists($plupload_lang_file)) {
						echo '<script type="text/javascript" src="'.$plupload_lang_file.'"></script>';
					}
				}
			?>

			<script type="text/javascript">
			$(function() {
				$("#uploader").pluploadQueue({
					runtimes : 'gears,flash,silverlight,browserplus,html5',
					url : 'process-upload.php',
					max_file_size : '<?php echo MAX_FILESIZE; ?>mb',
					chunk_size : '1mb',
					multipart : true,
					filters : [
						{title : "Allowed files", extensions : "<?php echo $options_values['allowed_file_types']; ?>"}
					],
					flash_swf_url : 'includes/plupload/js/plupload.flash.swf',
					silverlight_xap_url : 'includes/plupload/js/plupload.silverlight.xap'
					/*
					,init : {
						QueueChanged: function(up) {
							var uploader = $('#uploader').pluploadQueue();
							uploader.start();
						}
					}
					*/
				});

				$('form').submit(function(e) {
					var uploader = $('#uploader').pluploadQueue();

					if (uploader.files.length > 0) {
						uploader.bind('StateChanged', function() {
							if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
								$('form')[0].submit();
							}
						});
							
						uploader.start();

						uploader.bind('FileUploaded', function (up, file, info) {
							var uploaded_files = $('#uploaded_files').attr('value');

							// replace any commas on the filename
							var file_name = file.name;
							var fix_name = file_name.replace(/\,/g, '_');
							var appended_files = uploaded_files + fix_name + ',';

							$('#uploaded_files').attr('value',appended_files);
						});			

						return false;
					} else {
						alert('<?php _e("You must select at least one file to upload.",'cftp_admin'); ?>');
					}
			
					return false;
				});
			});
			</script>			
			<form action="upload-process-form.php" name="upload_by_client" id="upload_by_client" method="post" enctype="multipart/form-data">
				<input type="hidden" name="uploaded_files" id="uploaded_files" value="" />
				<div id="uploader">
					<p><?php _e("Your browser doesn't have Flash, Silverlight, Google Gears, BrowserPlus or HTML5 support. Please update your browser or install Adobe Flash to continue.",'cftp_admin'); ?></p>
				</div>
				<div align="right">
					<input type="submit" name="Submit" value="<?php _e('Continue','cftp_admin'); ?>" class="boton" />
				</div>
			</form>
	
	<?php
		} // end if for users count
	?>

</div>

<?php
	$database->Close();
	include('footer.php');
?>