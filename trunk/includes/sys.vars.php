<?php
include('sys.config.php'); // create this file before installing the system, and enter your database connection information there

define('MIN_USER_CHARS', 5);
define('MAX_USER_CHARS', 16);
define('MIN_PASS_CHARS', 5);
define('MAX_PASS_CHARS', 16);

define('COOKIE_EXP_TIME', 93600);

define('MAX_FILESIZE',32);
require_once('db_class.php');

require_once('site.options.php');

// User roles names
define('USER_ROLE_LVL_9', 'System Administrator');
define('USER_ROLE_LVL_8', 'Account Manager');
define('USER_ROLE_LVL_7', 'Uploader');

define('CURRENT_VERSION', 'r96');

$uri = 'http://code.google.com/p/clients-oriented-ftp/'; // cFTP webpage URI
$uri_txt = 'cFTP on Google Code';
$short_system_name = 'cFTP';
$full_system_name = 'cFTP (clients-oriented-ftp)';

// this settings are temporally located here. will be located elsewhere on following udpates
$logo_thumbnail_folder = '../img/custom/thumbs/';

// current language
$lang = $site_lang;

// i18n
define('I18N_DEFAULT_DOMAIN', 'cftp_admin');
require_once('i18n.php');
I18n::LoadDomain( "lang/{$lang}.mo", 'cftp_admin' );

// define languages
$available_langs = array();
$available_langs['en'] = __('English','cftp_admin');
$available_langs['es'] = __('Spanish','cftp_admin');
?>