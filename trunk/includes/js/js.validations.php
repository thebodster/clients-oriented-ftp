<?php
	// thanks to the following script for the idea http://www.webcheatsheet.com/javascript/form_validation.php
?>
<script type="text/javascript">

var error_bg = '#F4F8D5';
var error_color = '#505719';
var norm_bg = 'white';
var norm_color = 'black';

var error_title = "<?php echo $validation_errors_title; ?>\n\n";
var error_list = '';
var have_error = '';

function default_field() {
	document.getElementsByTagName('input')[0].focus();
}

function is_complete_no_err(field) {
	if (field.value.length == 0) {
		field.style.background=error_bg;
		field.style.color=error_color;
		have_error = 'y';
	}
	else {
		field.style.background=norm_bg;
		field.style.color=norm_color;
	}
}

function is_complete(field,error) {
	if (field.value.length == 0) {
		field.style.background=error_bg;
		field.style.color=error_color;
		error_list+=error+="\n";
	}
	else {
		field.style.background=norm_bg;
		field.style.color=norm_color;
	}
}

function is_length(field,minsize,maxsize,error) {
	if (field.value.length < minsize || field.value.length > maxsize) {
		field.style.background=error_bg;
		field.style.color=error_color;
		error_list+=error+="\n";
	}
	else {
		field.style.background=norm_bg;
		field.style.color=norm_color;
	}
}

function is_email(field,error) {
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var address = field.value;
	if (reg.test(address) == false) {
		field.style.background=error_bg;
		field.style.color=error_color;
		error_list+=error+="\n";
	}
	else {
		field.style.background=norm_bg;
		field.style.color=norm_color;
	}
}


function is_alpha(field,error) {
	var checkme = field.value;
	if (!(checkme.match(/^[a-zA-Z0-9]+$/))) {
		field.style.background=error_bg;
		field.style.color=error_color;
		error_list+=error+="\n";
	}
	else {
		field.style.background=norm_bg;
		field.style.color=norm_color;
	}
}

function is_match(field,field2,error) {
	if (field.value != field2.value) {
		field.style.background=error_bg;
		field.style.color=error_color;
		field2.style.background=error_bg;
		field2.style.color=error_color;
		error_list+=error+="\n";
	}
	else {
		field.style.background=norm_bg;
		field.style.color=norm_color;
		field2.style.background=norm_bg;
		field2.style.color=norm_color;
	}
}

</script>