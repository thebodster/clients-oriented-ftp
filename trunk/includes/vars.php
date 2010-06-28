<?php
// First language lesson ;)
$yes = 'Yes';
$no = 'No';

$userlevel_not_allowed = 'Your user account doesn\'t allow you to view this page. Please contact a system administrator if you need to access this functions.';

// install cftp
$install_database_title = 'Database'; // not in use
$install_database_desc = 'This data will allow the installer to set up the required tables correctly. It is taken from <em>/includes/sys.vars.php</em>, and shown so you can check if it\'s correct before attemping setup.'; // not in use
$install_db_name = 'DB name';
$install_db_host = 'Host';
$install_db_user = 'User';
$install_db_pass = 'Password';

$install_general_title = 'Basic system options';
$install_general_desc = 'You need to provide this data for a correct system installation. The site name will be visible along the system panel, and the client\'s lists.<br />Don\'t forget to edit <em>/includes/sys.vars.php</em> with your database settings before installing.';

$install_user_title = 'Default system administrator options';
$install_user_desc = 'This info will be appended to the user "admin", which is the default system user. It can\'t be deleted (and in this version, it isn\'t editable yet, so please pick your password carefuly). Password should be between <strong>6 and 12 characters long</strong>.';

$install_user_fullname = 'Full name';
$install_user_mail = 'Admin email';
$install_user_pass = 'Password';
$install_user_repeat = 'Repeat';

$install_button = 'Install';
$install_extra_info = 'After installing the system, you can go to the options page to set your timezone, prefered date display format and thubmnails parameters, besides being able to change the site options provided here.';

$install_ok = 'Congratulations! Everything is up and running.';
$install_ok2 = 'You may proceed to <a href="../index.php" target="_self">log in</a> with your newely created user. Remember, the username for that account is <strong>admin</strong>.';
$install_error = 'There seems to be an error. Please try again.';

$version = 'Version';

// pages titles
$page_title_basic = 'System Administrator';
$page_title_login = 'Log in';
$page_title_install = 'System setup';
$page_title_clients = 'Clients Administration';
$page_title_upload = 'Upload new files';
$page_title_home = 'Welcome to cFTP';
$page_title_logo = 'Logo configuration';
$page_title_newclient = 'Add new client';
$page_title_editclient = 'Edit client';
$page_title_newuser = 'Add system user';
$page_title_edituser = 'Edit system user';
$page_title_options = 'System options';
$page_title_users = 'Users administration';
$page_title_not_allowed = 'Access denied';

// login error
$login_admin_not_exists = 'The supplied username doesn\'t exist.';
$login_client_not_exists = 'The supplied username doesn\'t exist..';
$login_admin_pass_wrong = 'The supplied password is incorrect.';
$login_client_pass_wrong = 'The supplied password is incorrect.';


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


// login
$login_title = 'Login';
$login_tips = 'Please select your appropiate account type below.';
$login_tab_admin = 'Administrator';
$login_tab_client = 'Client';
$login_label_user = 'User';
$login_label_pass = 'Password';
$login_label_remember = 'Remember me';
$login_user_submit = 'Access Administrator';
$login_client_submit = 'Access file list';

$home_intro_text = 'Thank you for choosing cFTP. This software allows you to upload files for specific clients, and keep them stored for as long as you need them.';
$home_intro_text2 = 'cFTP lets choose a name and description for each individual file you upload, and relate it to an existing client or create a new one.
When the upload is complete, the system wil give you a link that you can share, where you client can see and download every file available under his account.';
$home_intro_text3 = 'Additionaly, you can select your own logo, that will appear in every client\'s page.';

$statistics_title = 'Statistics';
$statistics_clients = 'Clients';
$statistics_users = 'Users';
$statistics_logo = 'Personal logo';
$statistics_view = '[View]';
$stat_logo_change = '[Change]';

$upfname = 'Name';
$upfcli = 'Client';
$upfdes = 'File description';
$upffile = 'File';
$upclient = 'Client';
$upload_submit = 'Upload';
// message to show when there are no clients
$upload_no_clients = 'There are no clients at the moment.';
$upload_no_clients2 = 'Create a new one';
$upload_no_clients3 = 'to be able to upload files for that account.';

// New file email notification texts
$notify_email_subject = 'New file uploaded for you';
$notify_email_body = 'A new file has been uploaded for you to download.';
$notify_email_body2 = 'If you don\'t want to be notified about new files, please contact the uploader.';
$notify_email_body3 = 'You can access a list of all your files';
$notify_email_body4 = 'by logging in here';

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
$client_action_delete = 'Delete';
$client_action_view = 'View';
$noclients = 'There are no clients at the moment';
$delete_client_confirm = 'This will delete the folder and all of the client\'s files. Continue?';

$clients_table_id = 'ID';
$clients_table_name = 'Full name';
$clients_table_user = 'Login username';
$clients_table_address = 'Address';
$clients_table_phone = 'Telephone';
$clients_table_email = 'E-mail';
$clients_table_notify = 'Notify';
$clients_table_intcont = 'Internal Contact';
$clients_table_actions = 'Actions';
$clients_table_files = 'Files';
$clients_table_timestamp = 'Added on';


// Clients index template data
$creat_err1 = '<strong>¡Error!</strong>Can\'t open index.php';
$creat_err2 = '<strong>¡Error!</strong>Can\'t write index.php';

$days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
$months = array("January","February","March","April","May","June","July","August","September","October","November","December");

$cl_size = 'File size';
$cl_msg = 'Uploaded';
$delete = 'Delete';

// file uploaded
$file_upload_ok = 'File sent correctly';
$file_upload_error = 'Error sending file. Please try again';
$file_upload_types_error = 'This filetype is not allowed. Please check the options page and change it accordingly.<br /><strong>Warning</strong>: This could break security.';
$file_upload_exist_error = 'The file does\'t exist anymore, or it\'s empty. You cannot upload 0kb files.';
$file_upload_move = 'Error moving uploaded file. Please try again';
$up_filename = 'File name:';
$up_filetype = 'File type:';
$up_filesize = 'File size:';
$client_link = 'File uploaded correctly. Click here to see the file list for';

// Add client
$add_client_title = 'Add client account';
$add_client_label_name = 'Name';
$add_client_label_user = 'Login username';
$add_client_label_pass = 'Login password';
$add_client_label_pass2 = 'Repeat password';
$add_client_label_addr = 'Address';
$add_client_label_phone = 'Telephone';
$add_client_label_email = 'E-mail';
$add_client_label_notify = 'Notify new uploads by email';
$add_client_label_intcont = 'Internal contact';
$add_client_form_submit = 'Create account';
$edit_client_form_submit = 'Edit account';
$add_client_mail_info = 'This account information will be e-mailed to the address supplied above';

$add_client_ok = 'Client added correctly';
$add_client_error = 'The client could not be created. A folder with this name already exists.';
$add_client_folder_error = 'A folder for this client could not be created. Probably because of a server configuration.';
$add_client_exists = 'A client with this login username already exists.';
$add_client_mail_exists = 'A client with this e-mail address already exists.';
$edit_client_exists = 'There is no client with that ID to edit.';
$edit_client_ok = 'The client was edited correctly.';
$edit_client_error = 'There was an error. Please try again.';


// notification variables
$add_mail_body_user = 'username';
$add_mail_body_pass = 'password';
// clients specific
$add_client_mail_subject = 'Welcome to cFTP';
$add_client_mail_body = 'A new account was created for you. From now on, you can access the files that have been uploaded under your account using the following credentials:';
$add_client_mail_body_2 = 'Access the system administration here';
$add_client_mail_body_3 = 'Please contact the administrator if you need further assistance.';
$add_client_notify_ok = 'An e-mail notification with login information was sent to your client.';
$add_client_notify_error = 'E-mail notification couldn\'t be sent.';
// users specific
$add_user_mail_subject = 'Welcome to cFTP';
$add_user_mail_body = 'A new account was created for you. From now on, you can access the system administrator using the following credentials:';
$add_user_mail_body_2 = 'Access the system panel here';
$add_user_mail_body_3 = 'Thank you for using this system.';
$add_user_notify_ok = 'An e-mail notification with the login information was sent to the user.';
$add_user_notify_error = 'E-mail notification couldn\'t be sent.';

// Add user
$add_utitle = 'Add new system user';
$add_user_form_name = 'Name';
$add_user_form_user = 'Login username';
$add_user_form_pass = 'Login password';
$add_user_form_pass2 = 'Repeat password';
$add_user_form_email = 'E-mail';
$add_user_form_level = 'Role';
$add_user_form_submit = 'Add user';
$edit_user_form_submit = 'Modify user';

$user_role_lvl9 = 'System Administrator';
$user_role_lvl8 = 'Account Manager';
$user_role_lvl7 = 'Uploader';

$add_user_ok = 'User added correctly';
$add_user_error = 'There was an error. Please try again';
$add_user_exists = 'A user with this login name already exists.';
$add_user_mail_exists = 'A user with this e-mail address already exists.';
$edit_user_exists = 'There is no user with that ID to edit.';
$edit_user_ok = 'The user was edited correctly.';
$edit_user_error = 'There was an error. Please try again.';


// Users section
$nuser = 'Name:';
$user_edit = 'Edit';
$user_delete = 'Delete';
$delete_user_confirm = 'This will delete the user permanently. Continue?';

$view_user_id = 'ID';
$view_user_name = 'Full name';
$view_user_user = 'Login username';
$view_user_email = 'E-mail';
$view_user_level = 'Role';
$view_actions = 'Actions';
$view_user_timestamp = 'Added on';


// logo upload page
$logo_upload_description = 'Use this page to upload your company logo, or update your current uploaded one. This image will be shown to your clients when they access their file list.';
$logo_select_file = 'Select file';
$current_logo = 'Current logo:';
$logo_replace_file = 'Select image to upload';
$logo_upload_file = 'Upload';
$select_logo_preview = 'The picture on the left is not an actual representation of what they will see. The size on this preview is fixed, but remember that you can change the display size and picture quality for your client\'s pages on the <a href="options.php">options</a> section.';

$logo_uploaded_ok = 'The image was uploaded correctly.';
$select_logo_file_err = 'Please select an image file to upload';
$logo_uploaded_error = 'There was an error. Please try again.';
$logo_uploaded_filetye = 'The file you selected is not a valid image one. Please upload a jpg, gif or png formated logo picture.';

$logo_replace_info = 'The new image will act as your logo across your client\'s file lists.';


// options page

$title_location_options = 'System location options';
$desc_location_options = 'These options are to be changed only if you are moving the system to another place. Be careful when chaging them or everything will stop working.';
$options_base_uri = 'cFTP URI';

$title_general_options = 'General options';
$desc_general_options = 'Basic information to be shown around the site. The time format and zones values affect how the clients see the dates on their files lists.';
$options_site_name = 'Site name';
$options_template_list = 'Client\'s template';
$options_timezone = 'Timezone';
$options_timeformat = 'Time format';

$title_security_options = 'Security';
$desc_security_options = 'Be careful when changing this options. They could affect not only the system but the whole server it is installed on.<br /><strong>Important</strong>: Separate allowed file types with a "|".';
$options_security_filetypes = 'Allowed file types';

$title_thumbnails_options = 'Thumbnails';
$desc_thumbnails_options = 'Thumbnails are used on files lists. It\'s recommended to keep them small, unless you are using the system to upload only images and change the default client\'s template accordingly (cftp as a private image gallery?)';
$options_max_thumb_width = 'Max width';
$options_max_thumb_height = 'Max height';
$options_thumbnails_quality = 'Quality';

$title_logo_options = 'Own logo';
$desc_logo_options = 'Like the thumbnails options, this ones have to be changed taking in account the client\'s template design, since it can be shown there. Default template includes a left sidebar with the logo and instructions.';
$options_logo_width = 'Max width';
$options_logo_height = 'Max height';

$options_update = 'Update';
$options_missed_data = 'Please complete all the fields.';

$options_update_ok = 'Options updated succesfuly';
$options_update_error = 'There was an error. Please try again';
$options_update_fill_error = 'Some fields were not completed. Options could not be saved.';


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
$validation_no_pass2 = 'Password verification was not completed';
$validation_no_email = 'E-mail was not completed';
$validation_invalid_mail = 'E-mail address is not valid';
$validation_alpha_user = 'Username must be alphanumeric (a-z,A-Z,0-9 allowed)';
$validation_alpha_pass = 'Password must be alphanumeric (a-z,A-Z,0-9 allowed)';
$validation_length_user = 'Username length should be between '.MIN_USER_CHARS.' and '.MAX_USER_CHARS.' characters long.';
$validation_length_pass = 'Password length should be between '.MIN_PASS_CHARS.' and '.MAX_PASS_CHARS.' characters long.';
$validation_match_pass = 'Passwords did not match';
// users forms specific
$validation_no_level = 'User level was not specified';
// upload form specific
$validation_no_filename = 'File Name was not specified';
$validation_no_description = 'File description was not specified';
$validation_no_file = 'No file was selected';
$validation_no_client = 'Client was not specified';
// installation specific
$install_no_sitename = 'Sitename was not completed.';
$install_no_baseuri = 'cFTP URI was not completed.';

// others
$copyright = 'cFTP Free software (GPL2) | 2007 - ';
?>