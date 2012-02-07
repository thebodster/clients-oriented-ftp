	<div id="footer">
		<span><?php _e('cFTP Free software (GPL2) | 2007 - ', 'cftp_admin'); ?> <?php echo date("Y") ?> | <a href="<?php echo $GLOBALS['uri'];?>" target="_blank"><?php echo $GLOBALS['uri_txt'];?></a></span>
	</div>

</div> <!--wrapper-->

<script type="text/javascript">
	var menu=new menu.dd("menu");
	menu.init("menu","menuhover");
</script>

</body>
</html>
<?php ob_end_flush(); ?>