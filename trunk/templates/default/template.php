<?php	
	/* template name: Default */
	require_once('../../includes/vars.php');
	require_once('../../includes/sys.vars.php');
	require_once('../../includes/site.options.php');
	require_once('../../includes/functions.php');

	$this_template = '../../templates/default/';
	require($this_template.'vars.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this_user.' | '.$window_title; ?> | cFTP</title>
<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this_template; ?>main.css" />
<link rel="shortcut icon" href="../../favicon.ico" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script src="../../includes/js/jquery.tablesorter.min.js" type="text/javascript"></script>
</head>

<body>

<div id="header">
	<p id="cftptop">cFTP (clients-oriented-ftp)</p>
</div>

<div id="under_header">
	<div id="window_title"><?php echo '<strong>'.$this_user.'</strong> | '.$window_title; ?></div>
</div>

<div id="wrapper">

	<script type="text/javascript">
		$(document).ready(function()
			{
				$("#files_list").tablesorter( {
					sortList: [[0,0]], widgets: ['zebra'], headers: {
						4: { sorter: false },
						5: { sorter: false },
						6: { sorter: false }
					}
				});
			}
		);
	
		function confirm_file_delete() {
			if (confirm("<?php echo $confirm_file_delete; ?>")) return true ;
			else return false ;
		}
	</script>
	
	<div id="left_column">
	
		<?php if (file_exists('../../img/custom/logo.jpg')) { ?>
			<div id="current_logo" class="whitebox">
				<img src="../../includes/thumb.php?src=../img/custom/logo.jpg&amp;w=280&amp;sh=1&amp;ql=90&amp;type=tlogo" alt="" />
			</div>
			<div class="clear"></div>
		<?php } ?>

		<div id="help">
			<h2><?php echo $help_title; ?></h2>
			<?php echo $help_text; ?>
		</div>

	</div>
	
	<div id="right_column">
	
	<?php
	
		$sqllink = mysql_connect($host, $dbuser, $dbpass)or die('Cant connect to database');
		mysql_select_db($dbname)or die('Database not found');
	
		$query = 'SELECT * from tbl_files where client_user="' . $this_user .'"';
		$result = mysql_query($query);
		
		$count=mysql_num_rows($result);
		if (!$count) {
			echo $nofiles4u;
		}
		else {
	?>
	
			<table width="100%" border="0" cellspacing="0" cellpadding="0" id="files_list" class="tablesorter">
			<thead>
				<tr>
					<th><?php echo $file_name; ?></th>
					<th><?php echo $file_description; ?></th>
					<th><?php echo $file_size; ?></th>
					<th><?php echo $file_date; ?></th>
					<th><?php echo $file_download; ?></th>
					<th><?php echo $file_preview; ?></th>
					<th><?php echo $file_actions; ?></th>
				</tr>
			</thead>
			<tbody>
			
			<?php
				while($row = mysql_fetch_array($result)) {
			?>
			
				<tr>
					<td><?php echo $row['filename']; ?></td>
					<td><?php echo $row['description']; ?></td>
					<td><?php $entotal = $row['url']; $total = filesize($entotal); getfilesize($total); ?></td>
					<td>
						<?php
						$time_stamp=$row['timestamp']; //get timestamp
						$date_format=date($timeformat,$time_stamp); // formats timestamp in mm:dd:yy
						echo $date_format; // results here ... 02 : 11 : 07
						?>
					</td>
					<td>
						<div class="download_link">
							<a href="<?php echo $row['url']; ?>" target="_blank">
								<!--<img src="../../img/download.jpg" alt"<?php echo $file_download; ?>" />-->
								<?php echo $file_download; ?>
							</a>
						</div>
					</td>
					<td>
						<?php
							$extension = strtolower(substr($row['url'], -3));
							if (
								$extension == "gif" ||
								$extension == "jpg" ||
								$extension == "pjpeg" ||
								$extension == "jpeg" ||
								$extension == "png"
							) {
						?>
							<img src="../../includes/thumb.php?src=../upload/<?php echo $this_user; ?>/<?php echo $row['url']; ?>&amp;w=<?php echo $max_thumbnail_width; ?>&amp;sh=1&amp;ql=<?php echo $thumbnail_default_quality; ?>&amp;type=prev" class="thumbnail" alt="" />
						<?php } ?>
					</td>
					<td>
						<a onclick="return confirm_file_delete();" href="../../deletefile.php?client=<?php echo $this_user; ?>&amp;id=<?php echo $row['id']; ?>&amp;file=<?php echo $row['url']; ?>" target="_self">
							<img src="../../img/delete.jpg" alt"<?php echo $delete; ?>" />
						</a>
					</td>
				</tr>
			
			<?php
				}
			}
				mysql_close($sqllink);
			?>
	
				</tbody>
			</table>
	
	</div>

</div> <!-- wrapper -->

	<div id="footer">
		<span><?php echo $copyright; ?> <?php echo date("Y") ?> | <a href="<?php echo $uri;?>" target="_blank"><?php echo $uri_txt;?></a></span>
	</div>

</body>
</html>