<?php include('header.php'); ?>

	<script type="text/javascript">
		var missed_fields = "<?php echo $options_missed_data; ?>"

		function validoptions(){
			if (document.optionsform.this_install_title.value.length==0 ||
				document.optionsform.base_uri.value.length==0 ||
				document.optionsform.max_thumbnail_width.value.length==0 ||
				document.optionsform.max_thumbnail_height.value.length==0 ||
				document.optionsform.thumbnail_default_quality.value.length==0 ||
				document.optionsform.max_logo_width.value.length==0 ||
				document.optionsform.max_logo_height.value.length==0
			) {
				alert(missed_fields)
				return false;
			}
			document.forms[0].submit();
		}
	</script>

<div id="main">
	<h2><?php echo $page_title_options; ?></h2>

	<div class="options_box whitebox">

<?php
if ($_POST) { // did we clock update options?	

	$database->MySQLDB();

	$_POST=mysql_real_escape_array($_POST); // escape the values
	$keys = array_keys($_POST);

	$updated = 1;
	for ($i = 0; $i < count($keys); $i++) {
		$q = 'UPDATE tbl_options SET value="'.$_POST[$keys[$i]].'" WHERE name="'.$keys[$i].'"';
		$sql = mysql_query($q, $database->connection);
		$updated++;	
	}

	if ($updated) { //options updated succesfuly ?>
		<div class="message message_ok"><p><?php echo $options_update_ok; ?></p></div>
	<?php } else { 	// error updating options ?>
		<div class="message message_error"><p><?php echo $options_update_error; ?></p></div>
	<?php
	}

}
else { // just entering the options page
?>

		<form action="" name="optionsform" method="post" target="_self">
			<h3><?php echo $title_general_options; ?></h3>
			<h4><?php echo $desc_general_options; ?></h4>
			
			<label for="this_install_title"><?php echo $options_site_name; ?></label><input name="this_install_title" id="this_install_title" value="<?php echo $this_install_title; ?>" /><br />
			<label for="base_uri"><?php echo $options_base_uri; ?></label><input name="base_uri" id="base_uri" value="<?php echo $baseuri; ?>" /><br />
			<label for="selected_clients_template"><?php echo $options_template_list; ?></label>
				<select name="selected_clients_template" disabled="disabled">
					<option value="default">Default</option>
				</select>
				<?php include_once('includes/timezones.php'); ?>
			<label for="timeformat"><?php echo $options_timeformat; ?></label><input name="timeformat" id="timeformat" value="<?php echo $timeformat; ?>" /><br />
			
			<div class="options_divide"></div>
	
			<h3><?php echo $title_thumbnails_options; ?></h3>
			<h4><?php echo $desc_thumbnails_options; ?></h4>
			
			<div class="options_column options_col_left">
				<label for="max_thumbnail_width"><?php echo $options_max_thumb_width; ?></label><input name="max_thumbnail_width" id="max_thumbnail_width" value="<?php echo $max_thumbnail_width; ?>" /><br />
				<label for="max_thumbnail_height"><?php echo $options_max_thumb_height; ?></label><input name="max_thumbnail_height" id="max_thumbnail_height" value="<?php echo $max_thumbnail_height; ?>" /><br />
<!--
				<label for="site_name"><?php echo $options_thumbnails_folder; ?>:</label><input name="site_name" id="site_name" value="<?php echo $thumbnails_folder; ?>" />
-->
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
				<input type="button" name="Submit" value="<?php echo $options_update; ?>" class="boton" onclick="validoptions();" />
			</div>

		</form>

<?php } ?>

	</div>

	</div>

<?php include('footer.php'); ?>