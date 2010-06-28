<?php
$textboxlist = 1;
$allowed_levels = array(9);
require_once('includes/includes.php');
$page_title = $page_title_options;
include('header.php');

$database->MySQLDB();
require_once('includes/form_validation_class.php');

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
	$_POST[$keys[4]] = str_replace(',','|',$_POST[$keys[4]]);

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
					$sql = mysql_query($q, $database->connection);
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

<script type="text/javascript">		
	window.addEvent('load', function(){
		// With custom adding keys 
		var t = new TextboxList('allowed_file_types', {unique: true, bitsOptions:{editable:{addKeys: 188}}});
	});
</script>

<div id="main">
	<h2><?php echo $page_title; ?></h2>

	<div class="options_box whitebox">

	<?php
	if ($query_state == 'ok') {
		echo '<div class="message message_ok"><p>'.$options_update_ok.'</p></div>';
	}
	else if ($query_state == 'err') {
		echo '<div class="message message_error"><p>'.$options_update_error.'</p></div>';
	}
	else {
		if ($query_state == 'err_fill') {
			echo '<div class="message message_error"><p>'.$options_update_fill_error.'</p></div>';
		}
	?>

		<?php include_once('includes/js/js.validations.php'); ?>
	
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
			<h3><?php echo $title_location_options; ?></h3>
			<h4><?php echo $desc_location_options; ?></h4>
			<label for="base_uri"><?php echo $options_base_uri; ?></label><input name="base_uri" id="base_uri" value="<?php echo $baseuri; ?>" /><br />

			<div class="options_divide"></div>

			<h3><?php echo $title_general_options; ?></h3>
			<h4><?php echo $desc_general_options; ?></h4>
			
			<label for="this_install_title"><?php echo $options_site_name; ?></label><input name="this_install_title" id="this_install_title" value="<?php echo $this_install_title; ?>" /><br />
			<label for="selected_clients_template"><?php echo $options_template_list; ?></label>
				<select name="selected_clients_template" id="selected_clients_template" disabled="disabled">
					<option value="default">Default</option>
				</select>
				<?php include_once('includes/timezones.php'); ?>
			<label for="timeformat"><?php echo $options_timeformat; ?></label><input name="timeformat" id="timeformat" value="<?php echo $timeformat; ?>" /><br />
			
			<div class="options_divide"></div>

			<h3><?php echo $title_security_options; ?></h3>
			<h4><?php echo $desc_security_options; ?></h4>
			<label for="allowed_file_types"><?php echo $options_security_filetypes; ?></label><input name="allowed_file_types" id="allowed_file_types" value="<?php echo $allowed_file_types; ?>" /><br />

			<div class="options_divide"></div>
	
			<h3><?php echo $title_thumbnails_options; ?></h3>
			<h4><?php echo $desc_thumbnails_options; ?></h4>
			
			<div class="options_column options_col_left">
				<label for="max_thumbnail_width"><?php echo $options_max_thumb_width; ?></label><input name="max_thumbnail_width" id="max_thumbnail_width" value="<?php echo $max_thumbnail_width; ?>" /><br />
				<label for="max_thumbnail_height"><?php echo $options_max_thumb_height; ?></label><input name="max_thumbnail_height" id="max_thumbnail_height" value="<?php echo $max_thumbnail_height; ?>" /><br />
			</div>
			<div class="options_column options_col_right">
				<label for="thumbnail_default_quality"><?php echo $options_thumbnails_quality; ?></label><input name="thumbnail_default_quality" id="thumbnail_default_quality" value="<?php echo $thumbnail_default_quality; ?>" />
			</div>
			<div class="clear"></div>
			<div class="options_divide"></div>
			
			<h3><?php echo $title_logo_options; ?></h3>
			<h4><?php echo $desc_logo_options; ?></h4>
			
			<div class="options_column options_col_left">
				<label for="max_logo_width"><?php echo $options_logo_width; ?></label><input name="max_logo_width" id="max_logo_width" value="<?php echo $max_logo_width; ?>" />
			</div>
			<div class="options_column options_col_right">
				<label for="max_logo_height"><?php echo $options_logo_height; ?></label><input name="max_logo_height" id="max_logo_height" value="<?php echo $max_logo_height; ?>" />
			</div>
			<div class="clear"></div>

			<div align="right">
				<input type="submit" name="Submit" value="<?php echo $options_update; ?>" class="boton" />
			</div>

		</form>

<?php } ?>

	</div>

</div>

<?php
	$database->Close();
	include('footer.php');
?>