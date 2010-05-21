<?php

$curver = '0.1';
$uri = 'http://code.google.com/p/clients-oriented-ftp/'; // cFTP webpage URI
$uri_txt = 'cFTP on Google Code';

// First language lesson ;)
$yes = 'Yes';
$no = 'No';

// install cftp

$install_database_title = 'Database';
$install_database_desc = 'This data will allow the installer to set up the required tables correctly. It is taken from <em>/includes/sys.vars.php</em>, and shown so you can check if it\'s correct before attemping setup.';

$install_db_name = 'DB name';
$install_db_host = 'Host';
$install_db_user = 'User';
$install_db_pass = 'Password';

$install_missed_data = 'Please complete all the fields.';
$install_invalid_mail = 'Please insert a valid e-mail address.';
$install_pass_mismatch = 'Passwords do not match.';
$install_pass_short = 'Password length is wrong. Should be 6 to 12 characters.';
$install_pass_chars = 'Password has invalid characters. It should contain only letters and numbers.';

$install_general_title = 'Basic system options';
$install_general_desc = 'You need to provide this data for a correct system installation. Please remember to end the URI field with a "/" (this will be automatic on future revisions). The site name will be visible along the system panel, and the client\'s lists.';

$install_user_title = 'Default system administrator options';
$install_user_desc = 'This info will be appended to the user "admin", which is the default system user. It can\'t be deleted (and in this version, it isn\'t editable yet, so please pick your password carefuly). Password should be between <strong>6 and 12 characters long</strong>.';

$install_user_fullname = 'Full name';
$install_user_mail = 'Admin email';
$install_user_pass = 'Password';
$install_user_repeat = 'Repeat';

$install_button = 'Install';
$install_extra_info = '<p>After installing the system, you can go to the options page to set your timezone, prefered date display format and thubmnails parameters, besides being able to change the site options provided here.</p>';

$install_ok = 'Congratulations! Everything is up and running.';
$install_ok2 = 'You may proceed to <a href="../index.php" target="_self">log in</a> with your newely created user. Remember, the username for that account is <strong>admin</strong>.';
$install_error = 'There seems to be an error. Please try again.';

$version = 'Version';

$basictitle = 'System Administrator';

$login_err = 'Wrong user or password';
$login_err2 = 'Please try again';

// top menu bar
$mnu_home = 'Home';
$mnu_upload = 'Upload files';
// clients menu dropdown
$mnu_clients = 'Clients';
$mnu_add_cl = 'Add new';
$mnu_edit_cl = 'Manage clients';
// users menu dropdown
$mnu_users = 'Users';
$mnu_add_usr = 'Add new';
$mnu_edit_usr = 'Manage users';
// config dropdown
$mnu_config = 'Options';
$mnu_config_logo = 'Your logo';
$mnu_config_options = 'General options';


$tihome = 'Welcome to cFTP';
$tiupl = 'Upload files';
$ticli = 'Clients administration';
$ti_usrs = 'Users administration';
$page_title_logo = 'Logo configuration';
$page_title_options = 'System options';
$ticonfile = 'File upload';

$askuser = 'User';
$askpass = 'Password';
$btnlogin = 'Log in';

$txthome = '<p>Thank you for choosing cFTP. This software allows you to upload files for specific clients, and keep them stored for as long as you need them.</p>
<p>cFTP lets choose a name and description for each individual file you upload, and relate it to an existing client or create a new one.<br />
When the upload is complete, the system wil give you a link that you can share, where you client can see and download every file available under his account.</p>
<p>Additionaly, you can select your own <a href="logo.php">logo</a>, that will appear in every client\'s page.</p>';

$stattit = 'Statistics';
$statcli = 'Clients';
$statusr = 'Users';
$statlogo = 'Personal logo';
$statview = '[View]';
$stat_logo_change = '[Change]';

$upfname = 'Name';
$upfcli = 'Client';
$upfdes = 'File description';
$upffile = 'File';
$upclient = 'Client';
$upload_submit = 'Upload';
$upload_no_clients = '<p>There are no clients at the moment.</p><p>Please <a href="newclient.php" target="_self">create a new one</a> to be able to upload files for that account.</p>';
$notify_email_subject = 'New file uploaded for your account';
$notify_email_body = '<p>A new file has been uploaded for you to download.</p><p>If you don\'t want to be notified about new files, please contact the uploader.</p><p>You can access a list of all your files <a href="';
$notify_email_body2 = '" target="_blank">here</a>.</p>';

$notify_email_ok = 'Your client was notified about the file.';
$notify_email_error = 'E-mail notify couldn\'t be sent.';

$nwclialert = 'Please write a name for the new client.';
$exclialert = 'The selected client does not exist.';
$txtnoname = 'Please write a name for the file.';
$txtnodescrip = 'Please write a description for the file.';
$txtnofile = 'Select a file to upload.';
$alphaerror = 'You can only use letters, numbers and spaces on the users\'s name. Special characters are also disallowed.';

// View / Edit Clients section
$nclien = 'Name:';
$cldel = 'Delete';
$clview = 'View';
$noclients = 'There are no clients at the moment';
$confdel = 'This will delete the folder and all of the client\'s files. Continue?';

$view_cid = 'ID';
$view_cname = 'Full name';
$view_cuser = 'Login username';
$view_cadd = 'Address';
$view_cphone = 'Telephone';
$view_cmail = 'E-mails';
$view_ccont = 'Internal Contact';
$view_cnoti = 'Notify';
$view_actions = 'Actions';
$view_client_files = 'Files';
$view_client_timestamp = 'Added on';


// Clients index template data
$creat_err1 = '<strong>¡Error!</strong>Can\'t open index.php';
$creat_err2 = '<strong>¡Error!</strong>Can\'t write index.php';

$days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
$months = array("January","February","March","April","May","June","July","August","September","October","November","December");

$cl_size = 'File size';
$cl_msg = 'Uploaded';
$delete = 'Delete';

// file uploaded
$up_ok = 'File sent correctly';
$up_error = 'Error sending file. Please try again';
$up_filename = 'File name:';
$up_filetype = 'File type:';
$client_link = 'File uploaded correctly. Click here to see the file list for';

// Add client
$add_ctitle = 'Add client';
$add_cname = 'Name';
$add_cuser = 'Login username';
$add_cpass = 'Login password';
$add_cpass2 = 'Repeat password';
$add_cadd = 'Address';
$add_cphone = 'Telephone';
$add_cmail = 'E-mail';
$add_cnoti = 'Notify new uploads by email';
$add_ccont = 'Internal contact';
$add_client_submit = 'Create client';

$add_alrt_1 = 'Please add a name';
$add_alrt_2 = 'Please add a username';
$add_alrt_3 = 'Please add a password';
$add_alrt_4 = 'Please add a valid e-mail address';

$add_client_ok = 'Client added correctly';
$add_client_error = 'There was an error. Please try again';
$add_client_exists = 'A client with this login username already exists.';


// Add user
$add_utitle = 'Add new system user';
$add_user_form_name = 'Name';
$add_user_form_user = 'Login username';
$add_user_form_pass = 'Login password';
$add_user_form_pass2 = 'Repeat password';
$add_user_form_email = 'E-mail';
$add_user_form_level = 'Role';
$add_user_form_submit = 'Add user';

$user_role_lvl9 = 'System Administrator';
$user_role_lvl8 = 'Account Manager';
$user_role_lvl7 = 'Uploader';

$add_user_ok = 'User added correctly';
$add_user_error = 'There was an error. Please try again';
$add_user_exists = 'A user with this login name already exists.';


// Users section
$nuser = 'Name:';
$userdel = 'Delete';
$userconfdel = 'This will delete the user permanently. Continue?';

$view_user_id = 'ID';
$view_user_name = 'Full name';
$view_user_user = 'Login username';
$view_user_email = 'E-mail';
$view_user_level = 'Role';
$view_actions = 'Actions';
$view_user_timestamp = 'Added on';


// logo upload page
$logo_upload_description = '<p>Use this page to upload your company logo, or update your current uploaded one. This image will be shown to your clients when they access their file list.</p>';
$logo_select_file = 'Select file';
$current_logo = 'Current uploaded logo:';
$logo_replace_file = 'Select image to upload';
$logo_upload_file = 'Upload';
$select_logo_preview = '<p>The picture on the left is not an actual representation of what they will see. The size on this preview is fixed, but remember that you can change the display size and picture quality for your client\'s pages on the <a href="options.php">options</a> section.</p>';

$logo_uploaded_ok = '<p>The image was uploaded correctly.</p>';
$logo_uploaded_error = '<p>There was an error. Please try again.</p>';
$logo_uploaded_filetye = '<p>The file you selected is not a valid image one. Please upload a jpg, gif or png formated logo picture.</p>';

$logo_replace_info = '<p>The new image will act as your logo across your client\'s file lists.</p>';


// options page

$title_general_options = 'General options';
$desc_general_options = 'These options are to be changed only if you are moving the system to another place. Be careful when chaging them or everything will stop working.';
$options_site_name = 'Site name';
$options_base_uri = 'cFTP URI';
$options_template_list = 'Client\'s template';
$options_timezone = 'Timezone';
$options_timeformat = 'Time format';

$title_thumbnails_options = 'Thumbnails';
$desc_thumbnails_options = 'Thumbnails are used on files lists. It\'s recommended to keep them small, unless you are using the system to upload only images and change the default client\'s template accordingly (cftp as a private image gallery?)';
$options_max_thumb_width = 'Max width';
$options_max_thumb_height = 'Max height';
$options_thumbnails_folder = 'Folder';
$options_thumbnails_quality = 'Quality';

$title_logo_options = 'Own logo';
$desc_logo_options = 'Like the thumbnails options, this ones have to be changed taking in account the client\'s template design, since it can be shown there. Default template includes a left sidebar with the logo and instructions.';
$options_logo_width = 'Max width';
$options_logo_height = 'Max height';

$options_update = 'Update';
$options_missed_data = 'Please complete all the fields.';

$options_update_ok = 'Options updated succesfuly';
$options_update_error = 'There was an error. Please try again';


// pager buttons
$pager_first = 'First';
$pager_prev = 'Prev';
$pager_next = 'Next';
$pager_last = 'Last';


// form validation
$validation_errors_title = 'The following errors were found';

$validation_no_name = 'Name was not completed';
$validation_no_user = 'Username was not completed';
$validation_no_pass = 'Password was not completed';
$validation_no_email = 'E-mail was not completed';
$validation_invalid_mail = 'E-mail data is not a valid address';
$validation_alpha_user = 'Username must be alphanumeric (a-z,A-Z,0-9 allowed)';
$validation_alpha_pass = 'Password must be alphanumeric (a-z,A-Z,0-9 allowed)';
$validation_length_user = 'Username length should be between 6 and 12 characters long.';
$validation_length_pass = 'Password length should be between 6 and 12 characters long.';
$validation_match_pass = 'Passwords did not match';
// users forms specific
$validation_no_level = 'User level was not specified';


// others
$copyright = 'cFTP Free software (GPL2) | 2007 - ';
?>