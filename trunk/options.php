<?php
if (!$_POST) {
	$textboxlist = 1;
}
$allowed_levels = array(9);
require_once('includes/includes.php');
$page_title = __('System options','cftp_admin');
include('header.php');

$database->MySQLDB();
require_once('includes/classes/form-validation.php');

// replace | with , to use the tags system when showing the allowed filetypes on the form
$allowed_file_types = str_replace('|',',',$allowed_file_types);
// explode, sort, and implode the values to list them alphabetically
$allowed_file_types = explode(',',$allowed_file_types);
sort($allowed_file_types);
$allowed_file_types = implode(',',$allowed_file_types);

if ($_POST) {

	$_POST=mysql_real_escape_array($_POST); // escape the values
	$keys = array_keys($_POST);
	// change , to | on the allowed filetypes to store the value on the db
	//$_POST[$keys[4]] = str_replace(',','|',$_POST[$keys[4]]);

	// begin form validation
	for ($i = 0; $i < count($keys); $i++) {
		if ($_POST[$keys[$i]] == '') {
			$query_state = 'err_fill';
		}
		else {
			if ($query_state != 'err_fill') {
				$updated = 1;
				for ($j = 0; $j < count($keys); $j++) {
					$q = 'UPDATE tbl_options SET value="'.$_POST[$keys[$j]].'" WHERE name="'.$keys[$j].'"';
					$sql = $database->query($q, $database->connection);
					$updated++;
				}
				if ($updated){
					$query_state = 'ok';
				}
				else {
					$query_state = 'err';
				}
			}
		}
	}
}

?>

<?php if (!$_POST) { // Load TBL only when showing the form, not on saving ?>
	<script type="text/javascript">		
		$(function(){
			var t = new $.TextboxList('#allowed_file_types', {unique: true, bitsOptions:{editable:{addKeys: 188}}});
		});
	</script>
<?php } ?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>

	<div class="options_box whitebox">

	<?php
	if ($query_state == 'ok') {
		$msg = __('Options updated succesfuly.','cftp_admin');
		echo system_message('ok',$msg);
	}
	else if ($query_state == 'err') {
		$msg = __('There was an error. Please try again.','cftp_admin');
		echo system_message('error',$msg);
	}
	else {
		if ($query_state == 'err_fill') {
			$msg = __('Some fields were not completed. Options could not be saved.','cftp_admin');
			echo system_message('error',$msg);
		}
	?>

		<script type="text/javascript" src="includes/js/js.validations.php"></script>
	
		<script type="text/javascript">
		
			window.onload = default_field;
			var missed_fields = "<?php echo $options_missed_data; ?>";
		
			function validateform(theform){
	
				is_complete_no_err(theform.this_install_title);
				is_complete_no_err(theform.base_uri);
				is_complete_no_err(theform.timeformat);
				is_complete_no_err(theform.allowed_file_types);
				is_complete_no_err(theform.selected_clients_template);
				is_complete_no_err(theform.timezone);
				is_complete_no_err(theform.max_thumbnail_width);
				is_complete_no_err(theform.max_thumbnail_height);
				is_complete_no_err(theform.thumbnail_default_quality);
				is_complete_no_err(theform.max_logo_width);
				is_complete_no_err(theform.max_logo_height);
				if (have_error != '') {
					alert(missed_fields)
					have_error = '';
					return false;
				}
			}
	
		</script>
	
		<form action="options.php" name="optionsform" method="post" onsubmit="return validateform(this);">
			<h3><?php _e('System location options','cftp_admin'); ?></h3>
			<h4><?php _e('These options are to be changed only if you are moving the system to another place. Be careful when chaging them or everything will stop working.','cftp_admin'); ?></h4>
			<label for="base_uri"><?php _e('System URI','cftp_admin'); ?></label><input name="base_uri" id="base_uri" value="<?php echo $baseuri; ?>" /><br />

			<div class="options_divide"></div>

			<h3><?php _e('General options','cftp_admin'); ?></h3>
			<h4><?php _e('Basic information to be shown around the site. The time format and zones values affect how the clients see the dates on their files lists.','cftp_admin'); ?></h4>
			
			<label for="this_install_title"><?php _e('Site name','cftp_admin'); ?></label><input name="this_install_title" id="this_install_title" value="<?php echo $this_install_title; ?>" /><br />
			<label for="selected_clients_template"><?php _e("Client's template",'cftp_admin'); ?></label>
				<select name="selected_clients_template" id="selected_clients_template">
					<?php
						$templates = look_for_templates();
						foreach ($templates as $template) {
							echo '<option value="'.$template['folder'].'"';
								if($template['folder'] == $selected_clients_template) {
									echo ' selected="selected"';
								}
							echo '>'.$template['name'].'</option>';
						}
					?>
				</select>
			<label for="site_lang"><?php _e('Language','cftp_admin'); ?></label>
				<select name="site_lang" id="site_lang">
					<?php
						foreach ($available_langs as $lang_code => $lang_def) {
							echo '<option value="'.$lang_code.'"';
								if($lang_code == $site_lang) { echo 'selected="selected"'; }
							echo '>'.$lang_def.'</option>';
						}
					?>
				</select>
				<?php include_once('includes/timezones.php'); ?>
			<label for="timeformat"><?php _e('Time format','cftp_admin'); ?></label><input name="timeformat" id="timeformat" value="<?php echo $timeformat; ?>" /><br />
			
			<div class="options_divide"></div>

			<h3><?php _e('Security','cftp_admin'); ?></h3>
			<h4><?php _e('Be careful when changing this options. They could affect not only the system but the whole server it is installed on.','cftp_admin'); ?><br />
				<?php _e('<strong>Important</strong>: Separate allowed file types with a comma. You can navigate the box with the left/right arrows, backspace and delete keys.','cftp_admin'); ?></h4>
			<label for="allowed_file_types"><?php _e('Allowed file extensions','cftp_admin'); ?></label><input name="allowed_file_types" id="allowed_file_types" value="<?php echo $allowed_file_types; ?>" /><br />

			<div class="options_divide"></div>
	
			<h3><?php _e('Thumbnails','cftp_admin'); ?></h3>
			<h4><?php _e("Thumbnails are used on files lists. It is recommended to keep them small, unless you are using the system to upload only images and change the default client's template accordingly (cftp as a private image gallery?)",'cftp_admin'); ?></h4>
			
			<div class="options_column options_col_left">
				<label for="max_thumbnail_width"><?php _e('Max width','cftp_admin'); ?></label><input name="max_thumbnail_width" id="max_thumbnail_width" value="<?php echo $max_thumbnail_width; ?>" /><br />
				<label for="max_thumbnail_height"><?php _e('Max height','cftp_admin'); ?></label><input name="max_thumbnail_height" id="max_thumbnail_height" value="<?php echo $max_thumbnail_height; ?>" /><br />
			</div>
			<div class="options_column options_col_right">
				<label for="thumbnail_default_quality"><?php _e('JPG Quality','cftp_admin'); ?></label><input name="thumbnail_default_quality" id="thumbnail_default_quality" value="<?php echo $thumbnail_default_quality; ?>" />
			</div>
			<div class="clear"></div>
			<div class="options_divide"></div>
			
			<h3><?php _e('Company logo','cftp_admin'); ?></h3>
			<h4><?php _e("Like the thumbnails options, this ones have to be changed taking in account the client's template design, since it can be shown there. Default template includes a left sidebar with the logo and instructions.",'cftp_admin'); ?></h4>
			
			<div class="options_column options_col_left">
				<label for="max_logo_width"><?php _e('Max width','cftp_admin'); ?></label><input name="max_logo_width" id="max_logo_width" value="<?php echo $max_logo_width; ?>" />
			</div>
			<div class="options_column options_col_right">
				<label for="max_logo_height"><?php _e('Max height','cftp_admin'); ?></label><input name="max_logo_height" id="max_logo_height" value="<?php echo $max_logo_height; ?>" />
			</div>
			<div class="clear"></div>

			<div align="right">
				<input type="submit" name="Submit" value="<?php _e('Update','cftp_admin'); ?>" class="boton" />
			</div>

		</form>

<?php } ?>

	</div>

</div>

<?php
	$database->Close();
	include('footer.php');
?>