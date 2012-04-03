<?php
define('CURRENT_VERSION', 'r105');

//error_reporting(0);

define('ROOT_DIR', dirname(__FILE__));

if(file_exists(ROOT_DIR.'/sys.config.php')) {
	include(ROOT_DIR.'/sys.config.php'); // create this file before installing the system, and enter your database connection information there
}
else {
	echo '<h1>Missing a required file</h1>';
	echo "<p>The system couldn't find the configuration file <strong>sys.config.php</strong> that should be located on the <strong>includes</strong> folder.</p>
	<p>This file contains the database connection information, as well as the language and other important settings.</p>
	<p>If this is the first time you are trying to run ProjectSend, you can edit the sample file <strong>includes/sys.config.sample.php</strong> to create your own configuration information.<br />
		Then make sure to rename it to sys.config.php</p>";
	exit;
}

define('MIN_USER_CHARS', 5);
define('MAX_USER_CHARS', 16);
define('MIN_PASS_CHARS', 5);
define('MAX_PASS_CHARS', 16);

define('COOKIE_EXP_TIME', 93600);

require_once(ROOT_DIR.'/classes/database.php');
require_once(ROOT_DIR.'/site.options.php');

// User roles names
define('USER_ROLE_LVL_9', 'System Administrator');
define('USER_ROLE_LVL_8', 'Account Manager');
define('USER_ROLE_LVL_7', 'Uploader');

define('SYSTEM_URI','http://code.google.com/p/clients-oriented-ftp/');
define('SYSTEM_URI_LABEL','ProjectSend on Google Code');
define('SYSTEM_NAME','ProjectSend'); // Previously cFTP

define('LOGO_THUMB_FOLDER','../img/custom/thumbs/');

// current language
$lang = SITE_LANG;

// i18n
define('I18N_DEFAULT_DOMAIN', 'cftp_admin');
require_once(ROOT_DIR.'/i18n.php');
I18n::LoadDomain(ROOT_DIR."/../lang/{$lang}.mo", 'cftp_admin' );

?>