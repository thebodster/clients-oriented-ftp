<?php
// define language
$lang = 'en';

// Enter your database info here
define('DB_NAME', 'cftp1'); // database name
define('DB_HOST', 'localhost'); // database host (most of the times its localhost)
define('DB_USER', 'root'); // user related to cftp's database
define('DB_PASSWORD', ''); // the password for that user

define('MIN_USER_CHARS', 5);
define('MAX_USER_CHARS', 16);
define('MIN_PASS_CHARS', 5);
define('MAX_PASS_CHARS', 16);

define('COOKIE_EXP_TIME', 93600);

define('MAX_FILESIZE',32);
require_once('db_class.php');

// User roles names
define('USER_ROLE_LVL_9', 'System Administrator');
define('USER_ROLE_LVL_8', 'Account Manager');
define('USER_ROLE_LVL_7', 'Uploader');

$curver = 'r87';
$uri = 'http://code.google.com/p/clients-oriented-ftp/'; // cFTP webpage URI
$uri_txt = 'cFTP on Google Code';
$short_system_name = 'cFTP';
$full_system_name = 'cFTP (clients-oriented-ftp)';

// this settings are temporally located here. will be located elsewhere on following udpates
$logo_thumbnail_folder = '../img/custom/thumbs/';

// i18n
define('I18N_DEFAULT_DOMAIN', 'cftp_admin');
require_once('i18n.php');
I18n::LoadDomain( "lang/{$lang}.mo", 'cftp_admin' );
?>