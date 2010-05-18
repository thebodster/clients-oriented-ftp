<?php include('header.php'); ?>

<script type="text/javascript">
	var txtnewclient = "<?php echo $nwclialert; ?>"
	var txtextclient = "<?php echo $exclialert; ?>"
	var alphaerror = "<?php echo $alphaerror; ?>"
	var txtnoname = "<?php echo $txtnoname; ?>"
	var txtnodescrip = "<?php echo $txtnodescrip; ?>"
	var txtnofile = "<?php echo $txtnofile; ?>"

	function validateme(){

		if (document.uploadf.name.value.length==0) {
			alert(txtnoname)
			return false;
		}
		
		if (document.uploadf.description.value.length==0) {
			alert(txtnodescrip)
			return false;
		}

		if (document.uploadf.ufile.value.length==0) {
			alert(txtnofile)
			return false;
		}
	
		document.uploadf.submit();
	}

</script>

<div id="main">
	<h2><?php echo $tiupl; ?></h2>
	
	<div class="whiteform whitebox">
	
	<?php
		// count clients to show error or form
		$database->MySQLDB();

		$sql="SELECT * FROM tbl_clients";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);
		if (!$count) {
			echo $upload_no_clients;
		}
		else {
	?>

	
	<form action="uploadscript.php" name="uploadf" method="post" enctype="multipart/form-data" target="_self">

		<input type="hidden" name="MAX_FILE_SIZE" value="1000000000">

		<table border="0" cellspacing="1" cellpadding="1">
		  <tr>
			<td width="20%"><?php echo $upfname; ?></td>
			<td width="20%">&nbsp;</td>
			<td><input type="text" name="name" id="name" class="txtfield" /></td>
		  </tr>
		  <tr>
			<td><?php echo $upfdes; ?></td>
			<td>&nbsp;</td>
			<td><input type="text" name="description" id="description" maxlength="200" class="txtfield" /></td>
		  </tr>
		  <tr>
			<td><?php echo $upffile; ?></td>
			<td>&nbsp;</td>
			<td><input name="ufile" type="file" id="ufile" size="32" class="txtfield" /></td>
		  </tr>
		  <tr>
			<td><?php echo $upclient; ?></td>
			<td>&nbsp;</td>
			<td><select name="clientname" id="clientname" class="txtfield" >
					<?php
					
						$database->MySQLDB();
					
						$sql="SELECT client_user, name FROM tbl_clients";
						$result=mysql_query($sql);
						
						while($row = mysql_fetch_array($result)) {
							echo '<option value="'.$row['client_user'].'">' . $row['name'] . '</option>';
						}
						
						$database->Close();
					?>
				</select>
			</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
				<div align="right"><input type="button" name="Submit" value="<?php echo $upload_submit; ?>" class="boton" onclick="validateme();" /></div>
			</td>
		  </tr>
	  </table>

	</form>
	
	<?php } // end if for users count ?>

	</div>
</div>

<?php include('footer.php'); ?>