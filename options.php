<?php
$allowed_levels = array(9);
require_once('sys.includes.php');
$page_title = __('System options','cftp_admin');

$database->MySQLDB();
require_once('includes/classes/form-validation.php');

$textboxlist = 1;
include('header.php');

if ($_POST) {
	$_POST = mysql_real_escape_array($_POST); // escape the values
	$keys = array_keys($_POST);

	$options_total = count($keys);
	$options_filled = 0;

	// Check if all the options are filled
	for ($i = 0; $i < $options_total; $i++) {
		if ($_POST[$keys[$i]] == '') {
			$query_state = 'err_fill';
		}
		else {
			$options_filled++;
		}
	}

	if ($options_filled == $options_total) {
		$updated = 0;
		for ($j = 0; $j < $options_total; $j++) {
			$q = 'UPDATE tbl_options SET value="'.$_POST[$keys[$j]].'" WHERE name="'.$keys[$j].'"';
			$sql = mysql_query($q, $database->connection);
			$updated++;
		}
		if ($updated > 0){
			$query_state = 'ok';
		}
		else {
			$query_state = 'err';
		}
	}

}

// replace | with , to use the tags system when showing the allowed filetypes on the form
$allowed_file_types = str_replace('|',',',$allowed_file_types);
// explode, sort, and implode the values to list them alphabetically
$allowed_file_types = explode(',',$allowed_file_types);
sort($allowed_file_types);
$allowed_file_types = implode(',',$allowed_file_types);


// TODO: recrear las opciones para mostrar abajo las actualizadas
?>

<div id="main">
	<h2><?php echo $page_title; ?></h2>

	<div class="options_box whitebox">

	<?php
		if (isset($query_state)) {
			switch ($query_state) {
				case 'ok':
					$msg = __('Options updated succesfuly.','cftp_admin');
					echo system_message('ok',$msg);
					break;
				case 'err':
					$msg = __('There was an error. Please try again.','cftp_admin');
					echo system_message('error',$msg);
					break;
				case 'err_fill':
					$msg = __('Some fields were not completed. Options could not be saved.','cftp_admin');
					echo system_message('error',$msg);
					$show_options_form = 1;
					break;
			}
		}
		else {
			$show_options_form = 1;
		}
		
		if(isset($show_options_form)) {
	?>

		<script type="text/javascript">
			$(document).ready(function() {
				$(function(){
					var t = new $.TextboxList('#allowed_file_types', {unique: true, bitsOptions:{editable:{addKeys: 188}}});
				});

				$("form").submit(function() {
					clean_form(this);

					is_complete_all_options(this,'<?php _e('Please complete all the fields.','cftp_admin'); ?>');

					// show the errors or continue if everything is ok
					if (show_form_errors() == false) { return false; }
				});
			});
		</script>
	
		<form action="options.php" name="optionsform" method="post">
			<ul class="form_fields">
				<li>
					<h3><?php _e('System location options','cftp_admin'); ?></h3>
					<p><?php _e('These options are to be changed only if you are moving the system to another place. Be careful when chaging them or everything will stop working.','cftp_admin'); ?></p>
				</li>
				<li>
					<label for="base_uri"><?php _e('System URI','cftp_admin'); ?></label>
					<input name="base_uri" id="base_uri" value="<?php echo BASE_URI; ?>" />
				</li>

				<li class="options_divide"></li>

				<li>
					<h3><?php _e('General options','cftp_admin'); ?></h3>
					<p><?php _e('Basic information to be shown around the site. The time format and zones values affect how the clients see the dates on their files lists.','cftp_admin'); ?></p>
				</li>
				<li>
					<label for="this_install_title"><?php _e('Site name','cftp_admin'); ?></label>
					<input name="this_install_title" id="this_install_title" value="<?php echo THIS_INSTALL_SET_TITLE; ?>" />
				</li>
				<li>
					<label for="selected_clients_template"><?php _e("Client's template",'cftp_admin'); ?></label>
					<select name="selected_clients_template" id="selected_clients_template">
						<?php
							$templates = look_for_templates();
							foreach ($templates as $template) {
								echo '<option value="'.$template['folder'].'"';
									if($template['folder'] == TEMPLATE_USE) {
										echo ' selected="selected"';
									}
								echo '>'.$template['name'].'</option>';
							}
						?>
					</select>
					<?php include_once('includes/timezones.php'); ?>
				</li>
				<li>
					<label for="timeformat"><?php _e('Time format','cftp_admin'); ?></label>
					<input name="timeformat" id="timeformat" value="<?php echo TIMEFORMAT_USE; ?>" />
					<p class="field_note"><?php _e('For example, d/m/Y h:i:s will result in something like','cftp_admin'); ?> <strong><?php echo date('d/m/Y h:i:s'); ?></strong>.
					<?php _e('For the full list of available values, visit','cftp_admin'); ?> <a href="http://php.net/manual/en/function.date.php" target="_blank"><?php _e('this page','cftp_admin'); ?></a>.</p>
				</li>

				<li class="options_divide"></li>

				<li>
					<h3><?php _e('Security','cftp_admin'); ?></h3>
					<p><?php _e('Be careful when changing this options. They could affect not only the system but the whole server it is installed on.','cftp_admin'); ?><br />
					<?php _e('<strong>Important</strong>: Separate allowed file types with a comma. You can navigate the box with the left/right arrows, backspace and delete keys.','cftp_admin'); ?></p>
				</li>
				<li>
					<label for="allowed_file_types"><?php _e('Allowed file extensions','cftp_admin'); ?></label>
					<input name="allowed_file_types" id="allowed_file_types" value="<?php echo $allowed_file_types; ?>" />
				</li>

				<li class="options_divide"></li>

				<li>
					<h3><?php _e('Thumbnails','cftp_admin'); ?></h3>
					<p><?php _e("Thumbnails are used on files lists. It is recommended to keep them small, unless you are using the system to upload only images and change the default client's template accordingly.",'cftp_admin'); ?></p>
				</li>
				<li class="options_column">
					<div class="options_col_left">
						<label for="max_thumbnail_width"><?php _e('Max width','cftp_admin'); ?></label><input name="max_thumbnail_width" id="max_thumbnail_width" value="<?php echo THUMBS_MAX_WIDTH; ?>" /><br />
						<label for="max_thumbnail_height"><?php _e('Max height','cftp_admin'); ?></label><input name="max_thumbnail_height" id="max_thumbnail_height" value="<?php echo THUMBS_MAX_HEIGHT; ?>" /><br />
					</div>
					<div class="options_col_right">
						<label for="thumbnail_default_quality"><?php _e('JPG Quality','cftp_admin'); ?></label><input name="thumbnail_default_quality" id="thumbnail_default_quality" value="<?php echo THUMBS_QUALITY; ?>" />
					</div>
				</li>

				<li class="options_divide"></li>

				<li>		
					<h3><?php _e('Company logo','cftp_admin'); ?></h3>
					<p><?php _e("Like the thumbnails options, this ones have to be changed taking in account the client's template design, since it can be shown there. The default template uses a fixed width for the logo, however the Gallery one uses this settings to show the image on top.",'cftp_admin'); ?></p>
				</li>
				<li class="options_column">
					<div class="options_col_left">
						<label for="max_logo_width"><?php _e('Max width','cftp_admin'); ?></label><input name="max_logo_width" id="max_logo_width" value="<?php echo LOGO_MAX_WIDTH; ?>" />
					</div>
					<div class="options_col_right">
						<label for="max_logo_height"><?php _e('Max height','cftp_admin'); ?></label><input name="max_logo_height" id="max_logo_height" value="<?php echo LOGO_MAX_HEIGHT; ?>" />
					</div>
				</li>
				<li class="form_submit_li">
					<input type="submit" name="Submit" value="<?php _e('Update','cftp_admin'); ?>" class="button button_blue button_submit" />
				</li>
			</ul>
		</form>

		<?php } ?>

	</div>

</div>

<?php
	$database->Close();
	include('footer.php');
?>