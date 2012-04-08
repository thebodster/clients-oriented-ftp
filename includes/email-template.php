<?php
/**
 * Define the common header and footer markup used on all sent e-mails.
 *
 * @package ProjectSend
 */

/**
 * Styles that can be applied to images to prevent display issues on
 * webmail readers.
 */
$img_safe_style = 'display:block; margin:0; border:none;';

/**
 * Define the header. A table cell remains open and the content of the
 * e-mail is inserted there.
 */
$email_template_header = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.EMAIL_ENCODING.'" />
<title>%SUBJECT%</title>
</head>

<body style="background:#f4f4f4;" bgcolor="#f4f4f4">
<table width="550" border="0" cellspacing="0" cellpadding="0" style="margin:40px auto; background:#fff;	border:1px solid #ccc; -moz-border-radius:5px; -moz-box-shadow:3px 3px 5px #dedede; -webkit-border-radius:5px; -webkit-box-shadow:3px 3px 5px #dedede; border-radius:5px; box-shadow:3px 3px 5px #dedede;" bgcolor="#FFFFFF" align="center">
	<tr>
		<td style="padding:20px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
			<h3 style="font-family:Georgia, "Times New Roman", Times, serif; font-size:19px; font-weight:normal; padding-bottom:6px; border-bottom:1px dotted #CCCCCC; margin-bottom:20px; margin-top:0; color:#333333;">
				%SUBJECT%
			</h3>';

/**
 * Define the footer
 */
$email_template_footer = '</td>
	</tr>
	<tr>
		<td style="padding:20px; border-top:1px dotted #ccc;">
			<a href="'.SYSTEM_URI.'" target="_blank">
				<img src="'.BASE_URI.'img/icon-footer-email.jpg" alt="" style="'.$img_safe_style.'" />
			</a>
		</td>
	</tr>
</table>
</body>
</html>';
?>