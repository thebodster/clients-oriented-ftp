<?php
include ('../../templates/session_check.php');
$this_template = '../../templates/default/';
require($this_template.'vars.php');

$database->MySQLDB();
$sql = $database->query('SELECT * from tbl_files where client_user="' . $this_user .'"');
$sql2 = $database->query('SELECT * from tbl_clients where client_user="' . $this_user .'"');
while ($row = mysql_fetch_array($sql2)) {
	$user_full_name = $row['name'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $user_full_name.' | '.$window_title; ?> | <?php echo $short_system_name; ?></title>
<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this_template; ?>main.css" />
<link rel="shortcut icon" href="../../favicon.ico" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script src="../../includes/js/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="../../includes/js/jquery.tablesorter.pager.js" type="text/javascript"></script>
</head>

<body>

<div id="header">
	<p id="cftptop"><?php echo $full_system_name; ?></p>
	<a href="../../process.php?do=logout" target="_self"><img src="../../img/logout.gif" alt="Logout" id="logout" /></a>
</div>

<div id="under_header">
	<div id="window_title"><?php echo '<strong>'.$user_full_name.'</strong> | '.$window_title; ?></div>
</div>

<div id="wrapper">

	<script type="text/javascript">
		$(document).ready(function()
			{
				$("#files_list")
					.tablesorter( {
						sortList: [[0,0]], widgets: ['zebra'], headers: {
							4: { sorter: false },
							5: { sorter: false },
							6: { sorter: false }
						}
				})
				.tablesorterPager({container: $("#pager")})
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
		$count=mysql_num_rows($sql);
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
				while($row = mysql_fetch_array($sql)) {
			?>
			
				<tr>
					<td><?php echo $row['filename']; ?></td>
					<td><?php echo $row['description']; ?></td>
					<td><?php $entotal = $row['url']; $total = filesize($entotal); getfilesize($total); ?></td>
					<td>
						<?php
						$time_stamp=$row['timestamp']; //get timestamp
						$date_format=date($timeformat,$time_stamp); // formats timestamp
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
						<a onclick="return confirm_file_delete();" href="../../process.php?do=del_file&amp;client=<?php echo $this_user; ?>&amp;id=<?php echo $row['id']; ?>&amp;file=<?php echo $row['url']; ?>" target="_self">
							<img src="../../img/delete.jpg" alt"<?php echo $delete; ?>" />
						</a>
					</td>
				</tr>
			
			<?php
				}
			}
			?>
	
				</tbody>
			</table>

<?php if ($count > 10) { ?>
	<div id="pager" class="pager">
		<form>
			<input type="button" class="first pag_btn" value="<?php echo $pager_first; ?>" />
			<input type="button" class="prev pag_btn" value="<?php echo $pager_prev; ?>" />
			<span><strong>Page</strong>:</span>
			<input type="text" class="pagedisplay" disabled="disabled" />
			<input type="button" class="next pag_btn" value="<?php echo $pager_next; ?>" />
			<input type="button" class="last pag_btn" value="<?php echo $pager_last; ?>" />
			<span><strong>Show</strong>:</span>
			<select class="pagesize">
				<option selected="selected" value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option value="40">40</option>
			</select>
		</form>
	</div>
<?php } ?>	
	</div>

</div> <!-- wrapper -->

	<div id="footer">
		<span><?php echo $copyright; ?> <?php echo date("Y") ?> | <a href="<?php echo $uri;?>" target="_blank"><?php echo $uri_txt;?></a></span>
	</div>

</body>
</html>
<?php $database->Close(); ?>